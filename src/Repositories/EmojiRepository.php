<?php

namespace FancyFlux\Repositories;

use FancyFlux\EmojiData;
use Illuminate\Support\Str;

/**
 * Repository for emoji lookup by slug or emoticon.
 *
 * Provides a clean API for accessing emoji data using kebab-case slugs
 * generated from emoji names, or classic emoticons like :) and :(.
 * Used by the FANCY facade, flux:emoji component, and other components.
 *
 * Why: Centralizes emoji lookup logic and provides slug-based access
 * that's more developer-friendly than searching by character or name.
 * Emoticon support enables natural text-to-emoji conversion.
 *
 * @example FANCY::emoji()->list() // ['grinning-face', 'waving-hand', ...]
 * @example FANCY::emoji()->get('fire') // '🔥'
 * @example FANCY::emoji()->get(':)') // '😊'
 * @example FANCY::emoji()->find('fire') // ['char' => '🔥', 'name' => 'fire', 'slug' => 'fire', 'category' => 'symbols']
 * @example FANCY::emoji()->resolve('🔥') // '🔥' (passthrough)
 */
class EmojiRepository
{
    public const SKIN_TONES = ['light', 'medium-light', 'medium', 'medium-dark', 'dark'];

    /**
     * Cached emoji data indexed by slug.
     *
     * @var array<string, array{char: string, name: string, slug: string, category: string, skin_tones?: array<string>}>|null
     */
    protected ?array $emojiBySlug = null;

    /**
     * Cached list of all slugs.
     *
     * @var array<string>|null
     */
    protected ?array $slugList = null;

    /**
     * Classic emoticon to emoji character mapping.
     * Supports common text emoticons and converts them to UTF-8 emoji.
     *
     * @var array<string, string>
     */
    protected static array $emoticons = [
        // Smileys
        ':)' => '😊',
        ':-)' => '😊',
        ':]' => '😊',
        ':D' => '😃',
        ':-D' => '😃',
        ':d' => '😃',
        'xD' => '😆',
        'XD' => '😆',
        ':P' => '😛',
        ':-P' => '😛',
        ':p' => '😛',
        ';)' => '😉',
        ';-)' => '😉',
        ':*' => '😘',
        ':-*' => '😘',
        '<3' => '❤️',
        '</3' => '💔',

        // Sad/Negative
        ':(' => '😢',
        ':-(' => '😢',
        ':[' => '😢',
        ":'(" => '😭',
        ':\'(' => '😭',
        'D:' => '😧',
        ':/' => '😕',
        ':-/' => '😕',
        ':\'' => '😕',
        ':S' => '😖',
        ':s' => '😖',
        ':|' => '😐',
        ':-|' => '😐',

        // Surprised/Shocked
        ':O' => '😮',
        ':-O' => '😮',
        ':o' => '😮',
        'O_O' => '😳',
        'o_o' => '😳',
        'O.O' => '😳',
        ':0' => '😮',

        // Cool/Special
        'B)' => '😎',
        'B-)' => '😎',
        '8)' => '😎',
        '8-)' => '😎',
        '>:)' => '😈',
        '>:-)' => '😈',
        '>:(' => '😠',
        '>:-(' => '😠',
        ':@' => '😠',

        // Japanese/Kaomoji style
        '^_^' => '😊',
        '^.^' => '😊',
        '-_-' => '😑',
        '>_<' => '😣',
        'T_T' => '😭',
        'T.T' => '😭',
        'o/' => '👋',
        '\\o/' => '🙌',
        'm/' => '🤘',

        // Misc
        ':3' => '😺',
        '=)' => '😊',
        '=(' => '😢',
        '=D' => '😃',
        '<(' => '🐧',
        ':+1:' => '👍',
        ':-1:' => '👎',
        ':thumbsup:' => '👍',
        ':thumbsdown:' => '👎',
        ':ok:' => '👌',
        ':wave:' => '👋',
        ':clap:' => '👏',
        ':pray:' => '🙏',
        ':fire:' => '🔥',
        ':100:' => '💯',
        ':poop:' => '💩',
        ':skull:' => '💀',
        ':eyes:' => '👀',
    ];

    /**
     * Get all available emoji slugs.
     *
     * @return array<string>
     */
    public function list(): array
    {
        $this->ensureLoaded();

        return $this->slugList;
    }

    /**
     * Get emoji character by slug or emoticon.
     *
     * Supports:
     * - Kebab-case slugs: 'fire', 'grinning-face'
     * - Classic emoticons: ':)', ':(', ':D'
     * - Slack/Discord style: ':fire:', ':thumbsup:'
     *
     * @param string $slug Kebab-case emoji slug or emoticon
     * @return string|null Emoji character or null if not found
     */
    public function get(string $slug): ?string
    {
        // Check emoticons first (exact match)
        if (isset(self::$emoticons[$slug])) {
            return self::$emoticons[$slug];
        }

        // Check slug lookup
        $this->ensureLoaded();

        return $this->emojiBySlug[$slug]['char'] ?? null;
    }

    /**
     * Resolve any emoji input to a character.
     *
     * Accepts:
     * - Raw emoji characters (passthrough): '🔥' -> '🔥'
     * - Kebab-case slugs: 'fire' -> '🔥'
     * - Classic emoticons: ':)' -> '😊'
     *
     * @param string|null $input Emoji character, slug, or emoticon
     * @return string|null Emoji character or null if not resolvable
     */
    public function resolve(?string $input): ?string
    {
        if ($input === null || $input === '') {
            return null;
        }

        // If it's already an emoji character (multi-byte UTF-8), return as-is
        // Emoji characters are typically 3-4 bytes, while ASCII is 1 byte
        if (mb_strlen($input, 'UTF-8') <= 2 && strlen($input) > mb_strlen($input, 'UTF-8')) {
            return $input;
        }

        // Try to resolve as emoticon or slug
        return $this->get($input);
    }

    /**
     * Get all supported emoticons.
     *
     * @return array<string, string> Emoticon to emoji character mapping
     */
    public function emoticons(): array
    {
        return self::$emoticons;
    }

    /**
     * Check if input is a known emoticon.
     *
     * @param string $input Text to check
     * @return bool
     */
    public function isEmoticon(string $input): bool
    {
        return isset(self::$emoticons[$input]);
    }

    /**
     * Find emoji data by slug.
     *
     * @param string $slug Kebab-case emoji slug
     * @return array{char: string, name: string, slug: string, category: string}|null
     */
    public function find(string $slug): ?array
    {
        $this->ensureLoaded();

        return $this->emojiBySlug[$slug] ?? null;
    }

    /**
     * Check if a slug exists.
     *
     * @param string $slug Kebab-case emoji slug
     * @return bool
     */
    public function has(string $slug): bool
    {
        $this->ensureLoaded();

        return isset($this->emojiBySlug[$slug]);
    }

    /**
     * Search emojis by name or slug.
     *
     * @param string $query Search query
     * @param int $limit Maximum results to return
     * @return array<array{char: string, name: string, slug: string, category: string}>
     */
    public function search(string $query, int $limit = 20): array
    {
        $this->ensureLoaded();

        $query = strtolower($query);
        $results = [];

        foreach ($this->emojiBySlug as $emoji) {
            // Match against slug or name
            if (str_contains($emoji['slug'], $query) || str_contains(strtolower($emoji['name']), $query)) {
                $results[] = $emoji;
                if (count($results) >= $limit) {
                    break;
                }
            }
        }

        return $results;
    }

    /**
     * Get all emojis in a specific category.
     *
     * @param string $category Category key (e.g., 'smileys', 'people')
     * @return array<array{char: string, name: string, slug: string, category: string}>
     */
    public function category(string $category): array
    {
        $this->ensureLoaded();

        return array_values(array_filter(
            $this->emojiBySlug,
            fn($emoji) => $emoji['category'] === $category
        ));
    }

    /**
     * Get all category keys.
     *
     * @return array<string>
     */
    public function categories(): array
    {
        return array_keys(EmojiData::categories());
    }

    /**
     * Convert emoji name to kebab-case slug.
     *
     * @param string $name Emoji name (e.g., 'grinning face')
     * @return string Kebab-case slug (e.g., 'grinning-face')
     */
    public static function nameToSlug(string $name): string
    {
        return Str::slug($name);
    }

    /**
     * Ensure emoji data is loaded and indexed.
     */
    protected function ensureLoaded(): void
    {
        if ($this->emojiBySlug !== null) {
            return;
        }

        $this->emojiBySlug = [];
        $this->slugList = [];

        foreach (EmojiData::all() as $emoji) {
            $slug = self::nameToSlug($emoji['name']);
            $entry = [
                'char' => $emoji['char'],
                'name' => $emoji['name'],
                'slug' => $slug,
                'category' => $emoji['category'],
            ];
            if (isset($emoji['skin_tones'])) {
                $entry['skin_tones'] = $emoji['skin_tones'];
            }
            $this->emojiBySlug[$slug] = $entry;
            $this->slugList[] = $slug;
        }
    }

    /**
     * Get the 5-tone variant array for an emoji slug.
     *
     * @return array<string>|null [light, medium-light, medium, medium-dark, dark] or null if unsupported.
     */
    public function skinTones(string $slug): ?array
    {
        $this->ensureLoaded();

        return $this->emojiBySlug[$slug]['skin_tones'] ?? null;
    }

    /**
     * Whether an emoji slug supports skin-tone variants.
     */
    public function hasSkinTones(string $slug): bool
    {
        return $this->skinTones($slug) !== null;
    }

    /**
     * Return the toned variant for an emoji slug, or the base char if unsupported.
     */
    public function applyTone(string $slug, ?string $tone): ?string
    {
        $base = $this->get($slug);
        if ($base === null || $tone === null) {
            return $base;
        }
        $idx = array_search($tone, self::SKIN_TONES, true);
        if ($idx === false) {
            return $base;
        }
        $tones = $this->skinTones($slug);
        return $tones[$idx] ?? $base;
    }
}
