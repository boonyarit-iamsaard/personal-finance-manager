export default {
    '**/*.php,!_ide_helper_models.php,!_ide_helper.php': ['vendor/bin/duster lint'],
    '**/*': 'prettier --check --ignore-unknown',
};
