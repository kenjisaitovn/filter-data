$(document).ready(function () {
    var offset = 0;
    var limit = 100;
    var totalInsertedRow = 0;
    var spin = '<i class="fa fa-circle-o-notch fa-spin" style="font-size:13px"></i>';
    var titleFilterFb = 'Filter Fb';
    var titleFilterGg = 'Filter Google Query';
    var titleFilterQuery = 'Filter other Query';

    var btnFilterFb = $('#btnFilterFb');
    var btnFilterGgQuery = $('#btnFilterGgQuery');
    var btnFilterQuery = $('#btnFilterQuery');

    btnFilterFb.text(titleFilterFb);
    btnFilterGgQuery.text(titleFilterGg);
    btnFilterQuery.text(titleFilterQuery);

    //Events
    btnFilterFb.click(function () {
        $(this).prop('disabled', true);
        $(this).html(titleFilterFb + ' ' + spin);
        startFilterFbFilterFb();
    });

    $('#btnResetOffset').click(function () {
        offset = 0;
    });

    function startFilterFbFilterFb() {
        $.ajax({
            type: 'JSON',
            method: 'POST',
            url: currentPath + '/filter',
            data: {
                filterWhat: 'fb',
                offset: offset,
                limit: limit,
                _token: _token
            }
        }).done(function(response) {
            if(response.countOriginData > 0){
                // increase offset
                offset = offset + limit;
                totalInsertedRow += response.insertedRows;
                $('#processingRow').text(offset);
                $('#inserting').text(response.insertedRows);
                $('#inserted').text(totalInsertedRow);

                setTimeout(startFilterFbFilterFb, 100);
            }else{
                btnFilterFb.prop('disabled', false);
                btnFilterFb.text(titleFilterFb);
            }
        });
    }
    // Filter google query
    btnFilterGgQuery.click(function () {
        $(this).prop('disabled', true);
        $(this).html(titleFilterGg + ' ' + spin);
        startFilterGoogleQuery();
    });
    function startFilterGoogleQuery() {
        $.ajax({
            type: 'JSON',
            method: 'POST',
            url: currentPath + '/filter',
            data: {
                filterWhat: 'gg',
                offset: offset,
                limit: limit,
                _token: _token
            }
        }).done(function(response) {
            if(response.countOriginData > 0){
                // increase offset
                offset = offset + limit;
                totalInsertedRow += response.insertedRows;
                $('#processingRow').text(offset);
                $('#inserting').text(response.insertedRows);
                $('#inserted').text(totalInsertedRow);

                setTimeout(startFilterGoogleQuery, 100);
            }else{
                btnFilterGgQuery.prop('disabled', false);
                btnFilterGgQuery.text(titleFilterGg);
            }
        });
    }
    // Filter query
    btnFilterQuery.click(function () {
        $(this).prop('disabled', true);
        $(this).html(titleFilterQuery + ' ' + spin);
        // copy tbl requestall to requestall_copy
        $.ajax({
            type: 'JSON',
            method: 'GET',
            url: currentPath + '/copy-table-requestall-to-new-table'
        }).done(function(response) {
            // process on tbl requestall_copy
            startFilterQuery();
            // End
        });

    });
    function startFilterQuery() {
        $.ajax({
            type: 'JSON',
            method: 'POST',
            url: currentPath + '/filter',
            data: {
                filterWhat: 'other',
                offset: offset,
                limit: limit,
                _token: _token
            }
        }).done(function(response) {
            $('.result').html(response);
            if(response.countOriginData > 0){
                // increase offset
                offset = offset + limit;
                totalInsertedRow += response.insertedRows;
                $('#processingRow').text(offset);
                $('#inserting').text(response.insertedRows);
                $('#inserted').text(totalInsertedRow);

                setTimeout(startFilterQuery, 100);
            }else{
                btnFilterQuery.prop('disabled', false);
                btnFilterQuery.text(titleFilterQuery);
                saveLastStateOfTblRequesAllCopy();
            }
        });
    }
    var saveLastStateOfTblRequesAllCopy = function () {
        $.ajax({
            type: 'JSON',
            method: 'GET',
            url: currentPath + '/saveLastStateOfTblRequesAllCopy'
        }).done(function(response) {
            console.log('saved last state', response);
        });
    };

    $('.value').click(function () {
        var val = $(this).text();
        var t = $(this).select();
        document.execCommand('copy');
        console.log(val, t);
    });

    // End document ready
});