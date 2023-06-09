# Post Count Requirements

Allows board administrators to set post count requirements to view or post on a per-forum basis. Search results of topics/posts in post-restricted forums are removed if user doesn't have the required post count to view that forum.

## Installation

1. Download the extension
2. Copy the whole archive content to /ext/kaileymsnay/pcr
3. Go to your phpBB board > Administration Control Panel > Customise > Manage extensions > Post Count Requirements: enable

## Update instructions

1. Go to your phpBB board > Administration Control Panel > Customise > Manage extensions > Post Count Requirements: disable
2. Delete all files of the extension from /ext/kaileymsnay/pcr
3. Upload all the new files to the same locations
4. Go to your phpBB board > Administration Control Panel > Customise > Manage extensions > Post Count Requirements: enable
5. Purge the board cache

## Automated testing

We use automated unit tests to prevent regressions. Check out our build below:

master: [![Build Status](https://github.com/kaileymsnay/pcr/workflows/Tests/badge.svg)](https://github.com/kaileymsnay/pcr/actions)

## License

[GNU General Public License v2](license.txt)
