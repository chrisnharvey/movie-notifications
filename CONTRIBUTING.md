# Contribution Guide

Thank you for considering contributing to Movie Notifications! Please review the entire guide before sending a pull request.

## Bug Reports

To encourage active collaboration, we strongly encourage pull requests, not just bug reports. "Bug reports" may also be sent in the form of a pull request containing a failing test.

However, if you file a bug report, your issue should contain a title and a clear description of the issue. You should also include as much relevant information as possible and a code sample that demonstrates the issue. The goal of a bug report is to make it easy for yourself - and others - to replicate the bug and develop a fix.

Remember, bug reports are created in the hope that others with the same problem will be able to collaborate with you on solving it. Do not expect that the bug report will automatically see any activity or that others will jump to fix it. Creating a bug report serves to help yourself and others start on the path of fixing the problem.

## Which Branch?

All bug fixes should be sent to the latest stable branch. Bug fixes should never be sent to the master branch unless they fix features that exist only in the upcoming release.

Minor features that are fully backwards compatible with the current release may be sent to the latest stable branch.

Major new features should always be sent to the master branch, which contains the upcoming release.

## Security Vulnerabilities

If you discover a security vulnerability within Movie Notifications, please send an e-mail to Chris Harvey at chris@chrisnharvey.com. All security vulnerabilities will be promptly addressed.


## Coding Style

Movie Notifications follows the PSR-2 coding standard and the PSR-4 autoloading standard.

### Code Style Fixer

You may use the PHP-CS-Fixer to fix your code style before committing.

To get started, install the tool globally and check the code style by issuing the following terminal command from your project's root directory:

```
php-cs-fixer fix
```
