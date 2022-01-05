<?php

// helpers

function dsca_is_develop() {
    $host = substr(parse_url(get_home_url())['host'], 0, 7);
    return ( '192.168' === $host);
}

function dsca_is_microblog_post( $id ) {
    if ( has_post_format('aside', $id) )
        return true;
    
    if ( has_term('#micro-blog', 'category', $id ) )
        return true;

    return false;
}