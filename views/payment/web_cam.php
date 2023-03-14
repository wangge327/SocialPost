<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link href="<?= url("/"); ?>assets/libs/bootstrap/css/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="/webcam/assets/css/layout.css">
<link rel="stylesheet" href="/webcam/assets/css/font-awesome.min.css">
<link rel="stylesheet" href="/webcam/cropper/cropper.css">

<style>
  .buttonarea {
    display: inline-block;
    margin: 20px 0 30px 75px;
  }

  @media only screen and (max-width:679px) {
    #cropimage {
      overflow: hidden;
      height: 515px;
      width: 100%;
    }
  }

  @media only screen and (max-width:479px) {
    #cropimage {
      overflow: hidden;
      height: 400px;
      width: 100%;
    }
  }
</style>

<div class="avatararea">
  <div class="row">
    <div class="imagearea col-lg-5 col-md-5 col-sm-12">
      <div class="avatarimage" id="drop-area">
        @if( !empty($current_user->avatar) )
        <img src="uploads/avatar/{{ $current_user->avatar }}" alt="avatar" id="avatarimage" />
        @else
        <img src="webcam/assets/img/avatar.jpg" alt="avatar" id="avatarimage" />
        @endif
      </div>
      <div class="buttonarea">
        <button class="btn camera" data-backdrop="static" data-toggle="modal" data-target="#cameraModal">
          <i class="fa fa-camera"></i> &nbsp; Camera
        </button>
      </div>
    </div>
  </div>
</div>

<!-- The Make Selection Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Make a selection</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div id="cropimage">
          <img id="imageprev" src="webcam/assets/img/bg.png" />
        </div>

        <div class="progress">
          <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <div class="btngroup">
          <button type="button" class="btn upload1 float-left" data-dismiss="modal">Close</button>
          <button type="button" class="btn btnsmall" id="rotateL" title="Rotate Left"><i class="fa fa-undo"></i></button>
          <button type="button" class="btn btnsmall" id="rotateR" title="Rotate Right"><i class="fa fa-repeat"></i></button>
          <button type="button" class="btn btnsmall" id="scaleX" title="Flip Horizontal"><i class="fa fa-arrows-h"></i></button>
          <button type="button" class="btn btnsmall" id="scaleY" title="Flip Vertical"><i class="fa fa-arrows-v"></i></button>
          <button type="button" class="btn btnsmall" id="reset" title="Reset"><i class="fa fa-refresh"></i></button>
          <button type="button" class="btn camera1 float-right" id="saveAvatar">Save</button>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- The Camera Modal -->
<div class="modal" id="cameraModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Take a picture</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div id="my_camera"></div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn upload" data-dismiss="modal">Close</button>
        <button type="button" class="btn camera" onclick="take_snapshot()">Take a picture</button>
      </div>

    </div>
  </div>
</div>

<script>
  // Configure a few settings and attach camera
  function configure() {
    console.log("configure_start");
    Webcam.set({
      width: 300,
      height: 400,
      image_format: 'jpeg',
      jpeg_quality: 100,
      // dest_width: 350,
      // dest_height: 400,
      // crop_width: 400,
      // crop_height: 400
    });
    Webcam.attach('#my_camera');
    console.log("configure_end");
  }

  window.onload = function() {
    /* INPUT UPLOAD FILE */
    input.addEventListener('change', function(e) {
      var image = document.querySelector('#imageprev');
      var files = e.target.files;
      var done = function(url) {
        input.value = '';
        image.src = url;
        $alert.hide();
        $("#myModal").modal({
          backdrop: "static"
        });
        cropImage();

      };
      var reader;
      var file;
      var url;

      if (files && files.length > 0) {
        file = files[0];

        if (URL) {
          done(URL.createObjectURL(file));
        } else if (FileReader) {
          reader = new FileReader();
          reader.onload = function(e) {
            done(reader.result);
          };
          reader.readAsDataURL(file);
        }
      }
    });
  }

  /* CROP IMAGE AFTER UPLOAD */
  function cropImage() {
    var image = document.querySelector('#imageprev');
    var minAspectRatio = 0.5;
    var maxAspectRatio = 1.5;

    var cropper = new Cropper(image, {
      aspectRatio: 11 / 12,
      minCropBoxWidth: 220,
      minCropBoxHeight: 240,

      ready: function() {
        var cropper = this.cropper;
        var containerData = cropper.getContainerData();
        var cropBoxData = cropper.getCropBoxData();
        var aspectRatio = cropBoxData.width / cropBoxData.height;
        //var aspectRatio = 4 / 3;
        var newCropBoxWidth;
        cropper.setDragMode("move");
        if (aspectRatio < minAspectRatio || aspectRatio > maxAspectRatio) {
          newCropBoxWidth = cropBoxData.height * ((minAspectRatio + maxAspectRatio) / 2);

          cropper.setCropBoxData({
            left: (containerData.width - newCropBoxWidth) / 2,
            width: newCropBoxWidth
          });
        }
      },

      cropmove: function() {
        var cropper = this.cropper;
        var cropBoxData = cropper.getCropBoxData();
        var aspectRatio = cropBoxData.width / cropBoxData.height;

        if (aspectRatio < minAspectRatio) {
          cropper.setCropBoxData({
            width: cropBoxData.height * minAspectRatio
          });
        } else if (aspectRatio > maxAspectRatio) {
          cropper.setCropBoxData({
            width: cropBoxData.height * maxAspectRatio
          });
        }
      },
    });

    $("#scaleY").click(function() {
      var Yscale = cropper.imageData.scaleY;
      if (Yscale == 1) {
        cropper.scaleY(-1);
      } else {
        cropper.scaleY(1);
      };
    });

    $("#scaleX").click(function() {
      var Xscale = cropper.imageData.scaleX;
      if (Xscale == 1) {
        cropper.scaleX(-1);
      } else {
        cropper.scaleX(1);
      };
    });

    $("#rotateR").click(function() {
      cropper.rotate(45);
    });
    $("#rotateL").click(function() {
      cropper.rotate(-45);
    });
    $("#reset").click(function() {
      cropper.reset();
    });

    $("#saveAvatar").click(function() {
      var $progress = $('.progress');
      var $progressBar = $('.progress-bar');
      var avatar = document.getElementById('avatarimage');
      var $alert = $('.alert');
      canvas = cropper.getCroppedCanvas({
        width: 220,
        height: 240,
        imageSmoothingQuality: 'high',
      });

      $progress.show();
      $alert.removeClass('alert-success alert-warning');
      canvas.toBlob(function(blob) {
        var formData = new FormData();

        formData.append('avatar', blob, 'avatar.jpg');
        formData.append('user_id', '{{$_GET['
          uid ']}}');
        formData.append('csrf-token', '{{csrf_token()}}');
        $.ajax('/settings/updateAvatar', {
          method: 'POST',
          data: formData,
          processData: false,
          contentType: false,

          xhr: function() {
            var xhr = new XMLHttpRequest();

            xhr.upload.onprogress = function(e) {
              var percent = '0';
              var percentage = '0%';

              if (e.lengthComputable) {
                percent = Math.round((e.loaded / e.total) * 100);
                percentage = percent + '%';
                $progressBar.width(percentage).attr('aria-valuenow', percent).text(percentage);
              }
            };
            return xhr;
          },

          success: function(r_data) {
            console.log(r_data);
            $alert.show().addClass('alert-success').text('Upload success');
          },

          error: function() {
            //avatar.src = initialAvatarURL;
            $alert.show().addClass('alert-warning').text('Upload error');
          },

          complete: function() {
            $("#myModal").modal('hide');
            $progress.hide();
            initialAvatarURL = avatar.src;
            avatar.src = canvas.toDataURL();
          },
        });
      });

    });
  };
</script>