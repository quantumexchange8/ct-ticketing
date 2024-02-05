/**
 * Theme: Dastyle - Responsive Bootstrap 4 Admin Dashboard
 * Author: Mannatthemes
 * Tabledit Js
 */



$(function() {

    $('#makeEditable').SetEditable({ $addButton: $('#but_add')});

    // $('#submit_data').on('click',function() {
    //     var td = TableToCSV('makeEditable', ',');
    //     console.log(td);
    //     var ar_lines = td.split("\n");
    //     var each_data_value = [];

    //     for(i=0;i<ar_lines.length;i++)
    //     {
    //         each_data_value[i] = ar_lines[i].split(",");
    //     }

    //     for(i=0;i<each_data_value.length;i++)
    //     {
    //         if(each_data_value[i]>1)
    //         {
    //             console.log(each_data_value[i][2]);
    //             console.log(each_data_value[i].length);
    //         }

    //     }
    // });

    $('#submit_data').on('click', function () {
        var endpoint = $(this).data('endpoint');

        var td = TableToCSV('makeEditable', ',');
        console.log(td);

        var ar_lines = td.split("\n");
        var each_data_value = [];

        for (var i = 0; i < ar_lines.length; i++) {
            each_data_value[i] = ar_lines[i].split(",");
        }

        var dataToSend = [];

        if (endpoint === 'update-title') {
            for (var i = 0; i < each_data_value.length; i++) {
                if (each_data_value[i].length > 1) {
                    var rowData = {
                        id: each_data_value[i][0],
                        tsequence: each_data_value[i][1],
                        title: each_data_value[i][2]
                    };
                    dataToSend.push(rowData);
                }
            }
        } else if (endpoint === 'update-content') {
            for (var i = 0; i < each_data_value.length; i++) {
                if (each_data_value[i].length > 1) {
                    var rowData = {
                        id: each_data_value[i][0],
                        tid: each_data_value[i][1],
                        csequence: each_data_value[i][2],
                        subtitle: each_data_value[i][3],
                        content: each_data_value[i][4],
                        // d_image: each_data_value[i][5],
                    };
                    dataToSend.push(rowData);
                }
                var title = each_data_value[0][1];
            }
        } else if (endpoint === 'update-category') {
            for (var i = 0; i < each_data_value.length; i++) {
                if (each_data_value[i].length > 1) {
                    var rowData = {
                        id: each_data_value[i][0],
                        categoryname: each_data_value[i][1],
                    };
                    dataToSend.push(rowData);
                }
            }
        } else if (endpoint === 'update-sub') {
            for (var i = 0; i < each_data_value.length; i++) {
                if (each_data_value[i].length > 1) {
                    var rowData = {
                        catid: each_data_value[i][0],
                        subid: each_data_value[i][1],
                        subname: each_data_value[i][2],
                        subdesc: each_data_value[i][3],
                    };
                    dataToSend.push(rowData);
                }
                var catid = each_data_value[0][0];
            }
        } else if (endpoint === 'update-ticket-status') {
            for (var i = 0; i < each_data_value.length; i++) {
                if (each_data_value[i].length > 1) {
                    var rowData = {
                        id: each_data_value[i][0],
                        status: each_data_value[i][1],
                    };
                    dataToSend.push(rowData);
                }
            }
        }

        console.log(dataToSend);

        // Send data to the controller using AJAX
        $.ajax({
            type: 'POST',
            url: '/' + endpoint,  // Use the dynamically determined endpoint
            data: {
                data: dataToSend,
                titleID: title,
                catID: catid,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                console.log(response);

                if (endpoint === 'update-title') {
                    window.location.href = '/title-summary';
                } else if (endpoint === 'update-content') {
                    window.location.href = '/content-summary/' + encodeURIComponent(title);
                } else if (endpoint === 'update-category'){
                    window.location.href = '/support-category-summary';
                } else if (endpoint === 'update-sub'){
                    window.location.href = '/support-sub-summary/' + encodeURIComponent(catid);
                } else if (endpoint === 'update-ticket-status'){
                    window.location.href = '/ticket-status';
                }
            },
            error: function (error) {
                console.error(error);
            }
        });
    });

});

$('#mainTable').editableTableWidget().numericInputExample().find('td:first').focus();

