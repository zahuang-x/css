<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="_token" content="{{csrf_token()}}" />
  <title>アップロードサンプル</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.css">
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/dropzone.js"></script>
</head>

<body>
  <div class="container">
    <h3 class="jumbotron">下の枠にファイルをドラッグ＆ドロップ</h3>
    <form action="{{url('csv/upload')}}" method="post" name="file" enctype="multipart/form-data" class="dropzone" id="upload">
        @csrf
    </form>
  </div>

<script type="text/javascript">
        Dropzone.options.upload =
        {
            maxFilesize: 20,
            renameFile: function(file) {
                var dt = new Date();
                var time = dt.getTime();
               return time + '_' + file.name;
            },
            acceptedFiles: ".xlsx, .xls, .csv",
            dictInvalidFileType: "不適切な拡張子です、対応出来る種類は*.xlsx,*.xls,*.csvです。",
            dictFileTooBig:"対応出来るファイルサイズは20MB以内です。",
            addRemoveLinks: true,
            timeout: 50000,
            removedfile: function(file)
            {
                var name = file.upload.filename;
                $.ajax({
                    headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            },
                    type: 'POST',
                    url: '{{ url("csv/delete") }}',
                    data: {filename: name},
                    success: function (data){
                        console.log("ファイルを削除しました");
                    },
                    error: function(e) {
                        console.log(e);
                    }});
                    var fileRef;
                    return (fileRef = file.previewElement) != null ?
                    fileRef.parentNode.removeChild(file.previewElement) : void 0;
            },

            success: function(file, response)
            {
                console.log(response);
            },
            error: function(file, response)
            {
               return false;
            }
        };
</script>
</body>
</html>
