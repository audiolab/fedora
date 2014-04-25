<?php


function theme_mods_object($variables){

    global $language_content;

    if (empty($variables['mods'])){return;}
    if (empty($variables['mods']->mods)){return;}

    $mods = $variables['mods']->mods;  // -> Objeto MODS con el array en mods.
    $lang = $language_content->language;
    $ret = array();

    foreach($mods as $element => $content):
        if (empty($mods[$element])){continue;}
        $l_item = _islandora_get_localized($content);
        //$ret['_' . $element] = $l_item;

        $ret[$element] = theme("modselement__{$element}", array('element' => $l_item)); //array_reduce($l_item, '_islandora_arteleku_reduce_mods_array');

    endforeach;

    return $ret;

}

function theme_modselement($variables){
    $r = "";
    $item = $variables['element'];
    if (!is_array($item)){
        $r = $item;
    }else if(isset($item['value'])){
        $r = $item['value'];
    }else{
        $r = array_reduce($item, '_islandora_arteleku_reduce_mods_array');
    }

    if(!empty($result)){
        return $result . ' - ' . $r;
    }
    return $r;

}

function theme_modselement__tableOfContents($variables){
    $r = "";
    $item = $variables['element'];
    if (!is_array($item)){
        $r = $item;
    }else if(isset($item['value'])){
        $r = $item['value'];
    }else{
        $r = array_reduce($item, '_islandora_arteleku_reduce_mods_array');
    }
    $t="";
    if(!empty($r)){
        $r_split = explode(" ; ", $r);
        if(count($r_split)){
            $t = '<ul><li>' . join("</li><li>",$r_split) . '</ul>';
        }
    }

    if(!empty($result)){
        return $result . ' - ' . $t;
    }


    return $t;

}

function theme_modselement__name($variables){
    if(empty($variables)){return;}

    $items = $variables['element'];
    $ret = array();
    foreach($items as $item){
        isset($item['@valueURI']) ? $uri = $item['@valueURI'] : $uri = "";
        isset($item['namePart']['value']) ? $name = $item['namePart']['value'] : $name = "";
        $ret[] = l($name, $uri);
    }
    return '<ul><li>' . join("</li><li>", $ret) . "</li></ul>";

}

function theme_modselement__titleInfo($variables){
    if(empty($variables)){return;}
    $items = $variables['element'];
    $ret = array();
    foreach($items as $item){
        if(isset($item['@type'])){continue;}
        isset($item['title']['value']) ? $title = $item['title']['value'] : $title = "";
        isset($item['subTitle']['value']) ? $title .= ' - ' . $item['subTitle']['value'] : $title .= "";
        $ret[] = $title;
    }
    $t = join(' - ', $ret);
    ($t == "") ? $t = t('(none)') : $t=$t;
    return $t;

}


function theme_modselement__part($variables){
    if(empty($variables)){return;}
    $items = $variables['element'];
    $inTitle = $variables['title'];
    $ret = array();

    foreach($items as $item){
        $t = "";
        if(isset($item['detail']) && $inTitle){
            if(!isset($item['detail']['@type'])){continue;}
            if($item['detail']['@type'] != 'issue'){continue;}
            $ar = _islandora_get_localized($item['detail']);
            isset($item['detail']['@type']) ? $tipo = t($item['detail']['@type']) . ' ' : $tipo = '';
            $t = $tipo . array_reduce($ar, '_islandora_arteleku_reduce_mods_array');
        }else if (!$inTitle){
            $t = array_reduce($item, '_islandora_arteleku_reduce_mods_array');
        }
        $ret[] = $t;
    }
    $t = join(' - ', $ret);
    ($t == "" && !$inTitle) ? $t = t('(none)') : $t=$t;
    return $t;

}

function _islandora_arteleku_reduce_mods_array($result, $item){

    $r = "";

    if (!is_array($item)){
        $r = $item;
    }else if(isset($item['value'])){
        $r = $item['value'];
    }else{
        $r = array_reduce($item, '_islandora_arteleku_reduce_mods_array');
    }

    if(!empty($result)){
        return $result . ' - ' . $r;
    }
    return $r;

}

function _islandora_get_localized($item)
{
    global $language_content;
    $lang = $language_content->language;
    if (isset($item[$lang])){return $item[$lang];}
    if (isset($item['und'])){return $item['und'];}

    $allKeys = array_keys($item);
    return $item[$allKeys[0]];

}




