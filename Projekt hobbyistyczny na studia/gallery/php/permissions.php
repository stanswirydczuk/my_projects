<?php
function setPermissions($directoryPath) {
    chmod($directoryPath, 0755);
    chown($directoryPath, 'www-data');
}
?>