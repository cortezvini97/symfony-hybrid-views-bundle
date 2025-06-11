<?php

$directives = [
    "encore_entry_link_tags"=>function($expression){
        return "<?php echo importMP($expression, 'css') ?>";
    },
    "encore_entry_script_tags"=>function($expression){
        return "<?php echo importMP($expression, 'js') ?>";
    }
];


return $directives;