#!/usr/bin/env bash
commit_regex="^(feat|doc|fix|refactor|test)(\([a-z\_\-]+\)):\s[^A-Z]+$"
error_msg="Le message du commit n'est pas conforme"

if ! grep -qE $commit_regex .git/COMMIT_EDITMSG; then
    echo "$error_msg" >&2
    exit 1
fi
exit 0