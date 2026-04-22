## Summary

<!-- What does this PR change and why? -->

## Type of change

- [ ] Bug fix
- [ ] New feature
- [ ] Refactor / cleanup
- [ ] Documentation
- [ ] Translation

## Checklist

- [ ] Follows the [WordPress PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- [ ] All new user-facing strings are translatable (`__()`, `esc_html__()`, etc.)
- [ ] All new admin actions are protected by capability check + nonce
- [ ] All new `$_GET` / `$_POST` access uses `wp_unslash()` + sanitization
- [ ] All new outputs are escaped (`esc_html()`, `esc_attr()`, `esc_url()`, etc.)
- [ ] `CHANGELOG.md` updated
- [ ] No personal, server or credential data added to the repository
