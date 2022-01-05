<?php

// helpers

function dsca_is_develop() {
    $host = substr(parse_url(get_home_url())['host'], 0, 7);
    return ( '192.168' === $host);
}
