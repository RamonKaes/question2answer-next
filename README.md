Question2Answer
-----------------------------

[Question2Answer][Q2A] (Q2A) is a popular free open source Q&A platform for PHP/MySQL, used by over 20,898 [sites] in 40 languages.

**Note:** if you're using Q2A directly from git, make sure to use the master branch as that is the latest stable version. Or download an official release from the [Q2A website][Q2A].


----------

### Modernization (PHP 8.4+, 2026) — work in progress

The `feature/php84-modernization` branch is a ground-up rewrite of Q2A onto a modern,
strictly-typed **PHP 8.4+** stack (Symfony components, Twig, Doctrine DBAL), following
**PSR-12**, **WCAG 2.2 AA** accessibility and expanded **i18n** goals. Original authorship
is preserved — see [AUTHORS](AUTHORS) and [NOTICE](NOTICE).

- Plan & status: [ROADMAP.md](ROADMAP.md)
- Contributor & architecture guide: [CLAUDE.md](CLAUDE.md)
- Coding standard & tooling: [CONTRIBUTING.md](CONTRIBUTING.md)

The legacy 1.8.8 trees (`qa-include/`, `qa-theme/`, `qa-plugin/`, `qa-lang/`) remain in
place until feature parity is reached (removed in Phase 9). Existing plugins and themes
are **not** compatible with the rewrite.

Q2A is highly customisable with many awesome features:

- Asking and answering questions (duh!)
- Voting, comments, best answer selection, follow-on and closed questions.
- Complete user management including points-based reputation management.
- Create experts, editors, moderators and admins.
- Fast integrated search engine, plus checking for similar questions when asking.
- Categories (up to 4 levels deep) and/or tagging.
- Easy styling with CSS themes.
- Supports translation into any language.
- Custom sidebar, widgets, pages and links.
- SEO features such as neat URLs, microformats and XML Sitemaps.
- RSS, email notifications and personal news feeds.
- User avatars (or Gravatar) and custom fields.
- Private messages and public wall posts.
- Log in via Facebook or others (using plugins).
- Out-of-the-box WordPress 3+ integration.
- Out-of-the-box Joomla! 3.0+ integration (in conjunction with a Joomla! extension).
- Custom single sign-on support for other sites.
- PHP/MySQL scalable to millions of users and posts.
- Safe from XSS, CSRF and SQL injection attacks.
- Beat spam with captchas, rate-limiting, moderation and/or flagging.
- Block users, IP addresses, and censor words

Q2A also features an extensive plugin system:

- Modify the HTML output for a page with *layers*.
- Add custom pages to a Q2A site with *page modules*.
- Add extra content in various places with *widget modules*.
- Allow login via an external identity provider such as Facebook with *login modules*.
- Integrate WYSIWYG or other text editors with *editor/viewer modules*.
- Do something when certain actions take place with *event modules*.
- Validate and/or modify many types of user input with *filter modules*.
- Implement a custom search engine with *search modules*.
- Add extra spam protection with *captcha modules*.
- Extend many core Q2A functions using *function overrides*.


----------


All development is now taking place through GitHub. The collaborative development process is being managed by [Scott Vivian][1]. (Note that official releases are still distributed via the [Q2A website][Q2A].) See also:

- The [Q2A docs][2] for how to get started installing and using Q2A.
- The [Changelog][3] for what's new in each version.
- The [contributing file][4] for more information on how to get involved.


Thanks and enjoy!

Gideon & Scott


[Q2A]: http://www.question2answer.org/
[1]: http://www.question2answer.org/qa/user/Scott
[2]: https://docs.question2answer.org/
[3]: https://docs.question2answer.org/install/versions/
[4]: https://github.com/q2a/question2answer/blob/master/CONTRIBUTING.md
[releases]: https://github.com/q2a/question2answer/releases
[sites]: http://www.question2answer.org/sites.php
