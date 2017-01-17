$(document).ready(function () {
    var offset = 0;
    var limit = 100;
    var totalInsertedRow = 0;
    var spin = '<i class="fa fa-circle-o-notch fa-spin" style="font-size:13px"></i>';
    var titleFilterFb = 'Filter Fb';
    var titleFilterGg = 'Filter Google Query';

    var btnFilterFb = $('#btnFilterFb');
    var btnFilterGgQuery = $('#btnFilterGgQuery');

    btnFilterFb.text(titleFilterFb);
    btnFilterGgQuery.text(titleFilterGg);

    //Events
    btnFilterFb.click(function () {
        btnFilterFb.prop('disabled', true);
        btnFilterFb.html(titleFilterFb + ' ' + spin);
        startFilterFbFilterFb();
    });

    $('#btnResetOffset').click(function () {
        offset = 0;
    });

    function startFilterFbFilterFb() {
        $.ajax({
            type: 'JSON',
            method: 'POST',
            url: apiPath,
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
        btnFilterGgQuery.prop('disabled', true);
        btnFilterGgQuery.html(titleFilterGg + ' ' + spin);
        startFilterGoogleQuery();
    });
    function startFilterGoogleQuery() {
        $.ajax({
            type: 'JSON',
            method: 'POST',
            url: apiPath,
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
                btnFilterGgQuery.text(titleFilterFb);
            }
        });
    }
});