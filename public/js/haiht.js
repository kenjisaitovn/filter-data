$(document).ready(function () {
    var offset = 0;
    var limit = 100;
    var totalInsertedRow = 0;

    $('#btnFilterFb').click(function () {
        startFilterFbFilterFb();
    });
    $('#btnResetOffset').click(function () {
        offset = 0;
        $('#btnFilterFb').val('Filter Fb');
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
                $('#btnFilterFb').val('Finish');
            }
        });
    }
    // Filter google query
    $('#btnFilterGgQuery').click(function () {
        console.log('google');
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
                $('#btnFilterFb').val('Finish');
            }
        });
    }
});