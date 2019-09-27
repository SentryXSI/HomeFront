<?php
/*
meta_start
id          : 1
title       : Notes Component
heading     : My Notes
intro       : Notes
category    : notes
desc        : Welcome to the page
keys        : page keywords
author      : NinjaSentry
status      : published
robots      : index,follow
permissions : guest
container   : container-fluid
tags        : null
uri         : notes/index
date_start  : 2018-01-05
date_end    : 0000-00-00
last_edit   : 0000-00-00
wrap_heading : true
meta_end
*/

// temporary form token ^_-
$date        = \date('d-m-y H:i:s');
$appToken    = \ip2long( $_SERVER['SERVER_ADDR'] ) . $date;
$clientToken = \ip2long( $_SERVER['REMOTE_ADDR'] ) . $date;
$formToken   = \hash_hmac( 'sha512', $clientToken, $appToken );

?>
<div class="inner-content">
    <div class="ns-heading col-xs-12">
        <h2>My Notes</h2>
    </div>
    <div class="col-xs-12 col-sm-4">
        <h4>List</h4>
        <ul>
            <li>1</li>
        </ul>
    </div>
    <div class="col-xs-12 col-sm-8">
        <form action="notes/add" method="post" role="form">
            <div class="form-group">
                <label for="Title">Title</label>
                <input type="text" name="title" class="form-control" id="Title"  value="Today" />
            </div>
            <div class="form-group">
                <label for="Content">Content</label>
                <textarea name="content" class="form-control" id="Content" rows="10"></textarea>
            </div>
            <div class="form-group">
                <input type="submit" id="saveBtn" class="btn btn-success btn-lg" value="Add Note">
            </div>
            <input type="hidden" value="<?=$formToken;?>" id="Token" />
        </form>
        <div id="results"></div>
    </div>
    <div class="clearfix"></div>
</div>
<script>
    $(document).ready(function(){

        var saveBtn = $('#saveBtn');

        saveBtn.on('click', function( e ){

            e.preventDefault();

            var title   = $('#Title').val();
            var content = $('#Content').val();
            var token   = $('#Token').val();

            $.post('notes/ajax/add', {
                title: title,
                content: content,
                token: token
            }, function(data){
                $('#results').html(data).show();
            });
        });

    });
</script>