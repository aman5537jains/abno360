<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Abno360 Organization Selector</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>

    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container">
          <a class="navbar-brand" href="{{ URL('/') }}">Abno360</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNavDropdown">

          </div>
        </div>
    </nav>

    <div class="container">



<div class="row justify-content-center mt-5">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">Select organization</div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @foreach($contracts as $contract)
                    <li style='cursor:pointer' onclick="selectOrganization('{{urlencode($contract->getClass())}}','{{$contract->redirectUrl()}}')" class="list-group-item">{{$contract->getName()}} - {{$contract->getEmail()}} </li>
                    @endforeach
                  </ul>
            </div>
        </div>
    </div>
</div>
        <div class="row justify-content-center text-center mt-3">
            <div class="col-md-12">

            </div>
        </div>
    </div>
    <script
    src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script>

     let url = "{{route('abno-360-organization-login')}}";
     function selectOrganization (cls,redirect){
        $.get(url+"?cls="+cls,function(data){
            window.location = redirect;
        })
     }
</script>
</body>
</html>
