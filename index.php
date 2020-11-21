<?php
    require 'settings.php';

    //시간대
    date_default_timezone_set(timezone);

    // 변수 초기화
    $now_date = null;
    $data = null;
    $file_handle = null;
    $file_handle = null;
    $split_data = null;
    $message = array();
    $message_array = array();
    $error_message = array();
    $clean = array();


    //글 작성 저장 등등 다양한 거
    if( !empty($_POST['btn_submit']) ) {
        
        // 이름 표시 체크
	    if( empty($_POST['name']) ) {
		    $error_message[] = '이름을 작성하세요.';
        } else {
            $clean['name'] = htmlspecialchars( $_POST['name'], ENT_QUOTES);
            $clean['name'] = preg_replace( '/\\r\\n|\\n|\\r/', '', $clean['name']);
        } // 보안을 위한 거
        
        // 메세지 체크
	    if( empty($_POST['message']) ) {
		    $error_message[] = '메세지를 작성하세요.';
        }else {
            $clean['message'] = htmlspecialchars( $_POST['message'], ENT_QUOTES);
            $clean['message'] = preg_replace( '/\\r\\n|\\n|\\r/', '<br>', $clean['message']);
        } //보안 조아

        if( empty($error_message) ) {
            if ( $file_handle = fopen( file, "a") ) {

                // 작성 시간
		        $now_date = date("Y-m-d H:i:s");
	
		        // 내용?이라고 해야되나
		        $data = "'".$clean['name']."','".$clean['message']."','".$now_date."'\n";
	
		        // 파일에 작성
                fwrite( $file_handle, $data);
                
                //파일 닫기
                fclose( $file_handle );
            }
            header('Location: ./');
        }
    }

    //글 출력
    if( $file_handle = fopen( file,'r') ) {
        while( $data = fgets($file_handle) ){
    
            $split_data = preg_split( '/\'/', $data);
    
            $message = array(
                'name' => $split_data[1],
                'message' => $split_data[3],
                'post_date' => $split_data[5]
            );
            array_unshift( $message_array, $message);
        }
    
        // 파일 닫기
        fclose( $file_handle);
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <!--meta-->
        <meta http-equiv="Content-Type" charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="Keywords" content="<?php echo keywords; ?>" />
        <meta name="Description" content="<?php echo description; ?>" />
        <meta http-equiv="Subject" content="<?php echo subject?>" />
        <meta http-equiv="Generator" content="today-board" />
        <!--bootstrap-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    </head>
    <body>
        <header>
            <p class="brand">
                <h1 class="text-center">
                    <?php
                        echo name;
                    ?>
                </h1>
            </p>
        </header>
        <main>  
            <div class="container">
                <section>
                    <!--글 작성-->
                    <form method="POST">
                        <input class="form-control" type="text" placeholder="이름" value="익명" name="name">
                        <br>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="message" placeholder="메세지"></textarea>
                        <br>
                        <input type="submit" name="btn_submit" value="작성" class="btn btn-outline-primary">
                    </form>
                    <br>
                    <?php if( !empty($error_message) ): ?>
                        <div class="alert alert-danger" role="alert">
                        	<ul class="error_message">
		                    <?php foreach( $error_message as $value ): ?>
			                    <li><?php echo $value; ?></li>
		                    <?php endforeach; ?>
	                        </ul>
                        </div>
                    <?php endif; ?>
                </section>
                <hr>
                <section>
                    <!--글 출력-->
                    <?php if( !empty($message_array) ): ?>
                    <?php foreach( $message_array as $value ): ?>
                    <article>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $value['name']; ?>/<time><?php echo date('Y년m월d일 H:i', strtotime($value['post_date'])); ?></time></h5>
                                <p class="card-text"><?php echo $value['message']; ?></p>
                        </div>
                    </div>
                    </article>
                    <hr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </section>
            </div>
        </main>
        <footer>
            <!--copyright-->
            <p class="text-center">
                <small class="copyright">(c) 2020. <a href="https://github.com/pie3-14/today-board" target="_blank">today-board</a> all rights reserved.</small>
            </p>
        </footer>
        <!--부트스트랩 홈페이지에 있길래 넣어본 거-->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    </body>
</html>