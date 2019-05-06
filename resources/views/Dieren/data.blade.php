<!DOCTYPE html>
<html>
<head>
    <title>Dierentuin</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
    <br />
    <h3 align="center">Dierentuin</h3>
    <br />
    <div align="right">
        <button type="button" name="add" id="add_data" class="btn btn-success btn-sm">Voeg een dier toe</button>
    </div>
    <br />
    <table id="diers_table" class="table table-bordered" style="width:100%">
        <thead>
        <tr>
            <th>Diersoort</th>
            <th>Naam</th>
            <th>Aanpassen</th>
        </tr>
        </thead>
    </table>
</div>
<div id="dierModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="dier_form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Voeg een dier toe</h4>
                </div>
                <div class="modal-body">
                    {{csrf_field()}}
                    <span id="form_output"></span>
                    <div class="form-group">
                        <label>Geef de diersoort in</label>
                        <input type="text" name="diersoort" id="diersoort" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Geef de naam van het dier in</label>
                        <input type="text" name="naam" id="naam" class="form-control" />
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="dier_id" id="dier_id" value="" />
                    <input type="hidden" name="button_action" id="button_action" value="insert" />
                    <input type="submit" name="submit" id="action" value="Add" class="btn btn-info" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Sluiten</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#diers_table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('data.getdata') }}",
            "columns":[
                { "data": "diersoort" },
                { "data": "naam" },
                { "data": "action", orderable: false, searchable: false}
            ]
        });
    $('#add_data').click(function(){
        $('#dierModal').modal('show');
        $('#dier_form')[0].reset();
        $('#form_output').html('');
        $('#button_action').val('insert');
        $('#action').val('Voeg toe');
    });

    $('#dier_form').on('submit', function(event){
        event.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url: "{{ route("data.postdata") }}",
            method: "POST",
            data: form_data,
            dataType: "json",
            success: function (data) {
                if (data.error.length > 0) {
                    var error_html = '';
                    for (var count = 0; count < data.error.length; count++) {
                        error_html += '<div class="alert alert-danger">' + data.error[count] + '</div>';
                    }
                    $('#form_output').html(error_html);
                } else {
                    $('#form_output').html(data.success);
                    $('#dier_form')[0].reset();
                    $('#action').val('Add');
                    $('.modal-title').text('Voeg een dier toe');
                    $('#button_action').val('insert');
                    $('#diers_table').DataTable().ajax.reload();
                }
            }
        })
    });

        $(document).on('click', '.edit', function(){
            var id = $(this).attr("id");
            $('#form_output').html('');
            $.ajax({
                url:"{{route('data.fetchdata')}}",
                method:'get',
                data:{id:id},
                dataType:'json',
                success:function(data)
                {
                    $('#diersoort').val(data.diersoort);
                    $('#naam').val(data.naam);
                    $('#dier_id').val(id);
                    $('#dierModal').modal('show');
                    $('#action').val('wijzigen');
                    $('.modal-title').text('Dier aanpassen');
                    $('#button_action').val('update');
                }
            })
        });
    $(document).on('click', '.delete', function(){
        var id = $(this).attr('id');
        if(confirm("Ben je zeker dat je dit dier wilt verwijderen?"))
        {
            $.ajax({
                url:"{{route('data.removedata')}}",
                method:"get",
                data:{id:id},
                success:function(data)
                {
                    alert(data);
                    $('#diers_table').DataTable().ajax.reload();
                }
            })
        }
        else
        {
            return false;
        }
    });

});



</script>
</body>
</html>