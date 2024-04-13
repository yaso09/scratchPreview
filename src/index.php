<!DOCTYPE html>
<html>
<head>
  <style>
    .embed {
      background: #202020;
    }
    .userdata img {
      border-radius: 8px;
    }
    .withScrollbar {
      overflow-y: auto;

      scrollbar-color: #737373 #4d4d4d;

      scrollbar-track-color: #8c8c8c;
      scrollbar-thumb-color: #8c8c8c;
      scrollbar-arrow-color: #8c8c8c;
    }
    #copy_link {
      border: 0; border-radius: 8px;
    }
    .comment {
      border-radius: 8px;
      background: #202020;
      padding: 3vw; margin: 2vw;
    }
    #sendID_form {
      margin: 2vw;
    }
    #enter_id {
      height: 70px;
      width: 45rem;
      margin: 1vw;
      padding: 1vw;
      background: var(--bs-dark);
      border: 1px dashed rgb(93 85 250);
      border-radius: 8px;
      color: #fff;
    } #enter_id_btn {
      height: 70px;
      width: 15rem;
      border: 0;border-radius: 8px;
      color: #fff; font-weight: bolder;
      font-size: larger;
      background: rgb(93 85 250);
    }
    .bg-scratch {
      background: rgb(93 85 250);
    }
  </style>
</head>

<script>
  function getIdFromUrl(url) {
    const regex = /\/projects\/(\d+)\//;
    const match = url.match(regex);

    if (match) {
      return match[1];
    } else {
      return null;
    }
  }
</script>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
crossorigin="anonymous">

<body class="bg-dark text-white">

<p style='color: rgb(93 85 250)' class='bg-scratch'>Para ver!</p>

<?php

$route = $_SERVER["REQUEST_URI"];

if ($route === "/") {
  echo "<title>ScratchPreview</title>";
  echo "
  <div class='bg-scratch p-5'>
    <center>
      <h1>ScratchPreview</h1>
      <p>A custom pages for Scratch projects</p>
    </center>
  </div>
  <div class='container'><br><br>
    <div id='sendID_form'>
      <h4 style='margin-left: 8vw'>Create your page now!</h4>
      <center>
        <input id='enter_id' type='text'
        placeholder='https://scratch.mit.edu/projects/1000419315/'></input>
        <input id='enter_id_btn' type='button' value='See Page'></input>
      </center>
    </div><br>
  </div>
  <script>
    document.querySelector('#enter_id_btn').addEventListener('click', function() {
      window.location.href = getIdFromUrl(
        document.querySelector('#enter_id').value
      );
    });
  </script>
  <!--div class='p-5 container'>
    <h2>Cool pages, cool projects!</h2><hr>
    <div class='row'>
      <div style='cursor: pointer;' class='col bg-success p-5 m-4'>
        <h4>Learn how to make your pages more spectacular</h4>
        Read documentation
      </div>
      <div style='cursor: pointer; background: #6f42c1;' class='col p-5 m-4'>
        <h4>Publish your project as a website</h4>
        Just like a flash game site!
      </div>
    </div>
    <div style='cursor: pointer;' class='bg-black row p-5 m-2'>
      <h4>
        Need help?
        <small>
          <small>
            Ask the forums!
          </small>
        </small>
      </h4>
    </div>
  </div--!>
  ";
}
else if ($route === "/docs") {}
else {
  $reqt_id = explode("/", $route)[1];

  $api_url =
  "https://api.scratch.mit.edu";
  $api_url = "$api_url/projects/$reqt_id";

  $api = json_decode(file_get_contents($api_url, true));
  $author = $api->author;

  $pp_url =
  "https://cdn2.scratch.mit.edu/get_image/user/$author->id"."_60x60.png?v=";

  echo "<title>$api->title</title>";

  $embed_url = "https://turbowarp.org/$reqt_id/embed";

  echo "<div class='embed p-4'><div class='container'>";

  echo "
  <div class='userdata row m-2'>
    <div class='col-1'>
      <img style='height: 60px' src='$pp_url'></img>
    </div>
    <div class='col'>
      <div style='cursor: pointer' onclick='
      window.location.href = `https://scratch.mit.edu/projects/$reqt_id`;
      ' style='font-size: larger; font-weight: bolder;' class='row'>
        $api->title
      </div>
      <div style='cursor: pointer;' onclick='
      window.location.href = `https://scratch.mit.edu/users/$author->username`;
      ' class='row'>
        by $author->username
      </div>
    </div>
  </div>
  ";

  echo "
  <div class='m-3'>
    <div class='row'>
      <div class='col'>
        <iframe src='$embed_url'
        width='482' height='412'
        frameborder='1' scrolling='no'
        allowfullscreen='true'
        allowtransparency='true'>
        </iframe>
      </div>
      <div class='col col-md-6'>
        <div id='info1' style='border-radius: 8px; width: 70%; height: 193px;
        word-break: break-word'
        class='row bg-dark p-4 withScrollbar'>
          $api->instructions
        </div>
        <div class='row'><br></div>
        <div id='info2' style='border-radius: 8px; width: 70%; height: 192.7px;
        word-break: break-word'
        class='row bg-dark p-4 withScrollbar'>
          $api->description
        </div>
      </div>
    </div>
  </div>
  ";

  $history = $api->history->shared;
  $history = new DateTime($history);
  $history = $history->format("F j, Y g:i a");

  echo "
  <div class='row m-2'>
    <div style='font-size: large' class='col-3'>
      © $history
    </div>
    <div class='col-2'>
      <button id='copy_link'>Copy Link</button>
    </div>
  </div>
  ";

  echo "
  <script>
    document.querySelector('#copy_link').addEventListener('click', function() {
      navigator.clipboard.writeText(window.location.href).then(function() {
        window.alert('Copied');
      })
    });
  </script>
  ";

  echo "</div></div>";

  $comments = json_decode(file_get_contents(
    "https://api.scratch.mit.edu/users/$author->username/projects/$reqt_id/comments"
  ));

  echo "<div id='comments' class='container p-3'>";
  echo "<h2 style='padding: 2vw; font-weight: bolder'>Comments</h2>";

  foreach ($comments as $comment) {
    $comment_author = $comment->author;
    echo "
    <div class='comment'>
      <h5>$comment_author->username</h5><hr>
      <h6>$comment->content</h6>
    </div>
    ";
  }

  echo "</div>";

  echo "
  <script>
    const info1 = document.querySelector('#info1');
    const info2 = document.querySelector('#info2');

    if (!info1.innerHTML.trim()) {
      info1.remove();
      info2.style.height = '412px';
      info2.style.margin = '-20px';
    }
    if (!info2.innerHTML.trim()) {
      info2.remove();
      info1.style.height = '412px';
      info1.style.margin = '-20px';
    }

    if (window.location.hash == '#nocomment') {
      document.querySelector('#comments').remove();
    }
  </script>
  ";
}

?>

<br>
<footer>
  <center>
    <p>This page uses ScratchPreview created by Yasir Eymen KAYABAŞI</p>
    <small>
      <a class="text-white" href="./">Home page</a>,
      <a class="text-white" href="https://github.com/yaso09/ScratchPreview">Source code</a>
    </small><br><br><p></p>
  </center>
</footer>

<script
src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
crossorigin="anonymous"></script>

</body>
</html>

