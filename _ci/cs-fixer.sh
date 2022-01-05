#!/bin/bash

script_dir=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )

$script_dir/../tools/php-cs-fixer/vendor/bin/php-cs-fixer --config=$script_dir/../.php-cs-fixer.php --diff fix $script_dir/../src
