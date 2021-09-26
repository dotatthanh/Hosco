<!DOCTYPE html>
<html lang="en">
<head>
    <title>Thông tin đăng nhập</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"/>
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!--===============================================================================================-->
</head>
<body>

<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100">
            <div class="login100-pic js-tilt" data-tilt>
                <img src="images/img-01.png" alt="IMG">
                <div class="text-bold" style="font-weight: bold">
                    <p class="text-black">Cùng MASTER PRO quản lý bán hàng</p>
                    <p class="text-black">GỌI HỖ TRỢ: 0909 934 689</p>
                    <p class="text-black">Website: <a href="http://www.phanmembanhang.com">www.phanmembanhang.com</a></p>
                </div>
            </div>

            <form class="login100-form validate-form" {{ route('login') }} method="POST">
                @csrf
                <span class="login100-form-title text-uppercase">
                    Thông tin đăng nhập
                </span>

                <div class="form-group wrap-input100 validate-input">
                    <input id="tenantCode" type="text" class="form-control input100 @error('tenantCode') is-invalid @enderror" name="tenantCode"
                           value="{{ old('tenantCode') }}" placeholder="{{ __('Mã khách hàng') }}" required autocomplete="tenantCode">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-barcode" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="wrap-input100 validate-input">
                    <input id="username" type="text" class="form-control input100 @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}"
                           placeholder="Tên đăng nhập" required autocomplete="username">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-user" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="wrap-input100 validate-input" data-validate = "Password is required">
                    <input id="password" type="password" class="form-control input100 @error('password') is-invalid @enderror" name="password"
                           placeholder="Mật khẩu" required autocomplete="current-password">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-lock" aria-hidden="true"></i>
                    </span>
                </div>

{{--                <div class="wrap-input100">--}}
{{--                    <input class="form-check-input pull-left" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>--}}
{{--                    <label class="form-check-label pull-right" for="remember">--}}
{{--                        {{ __('Remember Me') }}--}}
{{--                    </label>--}}
{{--                </div>--}}

                <div class="container-login100-form-btn">
                    <button class="login100-form-btn">
                        Đăng nhập
                    </button>
                </div>

                <div class="text-center p-t-12">
                    {{--
						<span class="txt1">
							Forgot
						</span>
                    @if (Route::has('password.request'))
                        <a class="txt2" href="{{ route('password.request') }}">
                            {{ __('Your Password?') }}
                        </a>
                    @endif
                    --}}
                </div>

                {{--
                <div class="text-center p-t-136">
                    <a class="txt2" href="#">
                        Create your Account
                        <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
                    </a>
                </div>
                --}}
            </form>
        </div>
    </div>
</div>
@include('sweetalert::alert')
<!--===============================================================================================-->
<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
<script src="vendor/bootstrap/js/popper.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
<script src="vendor/tilt/tilt.jquery.min.js"></script>
<script >
    $('.js-tilt').tilt({
        scale: 1.1
    })
</script>
<!--===============================================================================================-->
<script src="js/main.js"></script>

</body>
</html>
