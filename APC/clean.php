<?php
/*
 *清空apc缓存
 * 在每次更新时都需要
 */
$result = apc_clear_cache ();
if( $result){
    echo 'Clean!',"\n";
}