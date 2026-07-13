<?php
/**
 * Title: Home category rows
 * Slug: now/home-rows
 * Categories: now, query
 * Inserter: no
 * Description: The home page's stack of per-category story rails (one row per live category).
 *
 * @package now-blog
 */

echo now_blog_category_rows( 4 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup with values escaped in the helper.
