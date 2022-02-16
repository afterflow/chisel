<?php


function chisel_project_data_path( $path = '' ) {

    return rtrim( config( 'chisel.data_path' ) . DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR . $path;

}
