<?php
interface captcha {
  public function __construct( $width, $height, $size, $map );
  public function __destruct();
  public function getCode();
  public function getImage();
}

$captcha = new class implements captcha {
  private $_im;
  private $_code;

  public function __construct( $width = 100, $height = 30, $size = 4, $map = '234678qwertyuipkjhfdazxcvbnm' ) {

    $this->_im = $im = imagecreate( $width, $height );
    imagecolorallocate( $im, 255, 255, 255 );

    $mw = $width - 1;
    $mh = $height - 1;
  
    for ( $i = 0; $i < 10; ++ $i )
      imageline( $im, mt_rand( 0, $mw ), mt_rand( 0, $mh ), mt_rand( 0, $mw ), mt_rand( 0, $mh ),
        imagecolorallocate( $im, mt_rand( 57, 197 ), rand( 57, 197 ), rand( 57, 197 ) )
      );

    for ( $i = 0; $i < 50; ++ $i )
      imagesetpixel( $im, mt_rand( 0, $width - 1 ), mt_rand( 0, $height - 1 ),
        imagecolorallocate( $im, mt_rand( 57, 197 ), mt_rand( 57, 197 ), mt_rand( 57, 197 ) ) 
      );

    for ( 
      $i = 0, $this->_code = '', $length = strlen( $map ) - 1; 
      $char = $map[mt_rand( 0, $length )], $i < $size;
      ++ $i, $this->_code .= $char
    ) imagestring( $im, 5, $i * 25 + mt_rand( 5, 10 ), mt_rand( 5, 10 ), $char, 
        imagecolorallocate( $im, mt_rand( 57, 197 ), mt_rand( 57, 197 ), mt_rand( 57, 197 ) ) 
      );

  }

  public function __destruct() {
    imagedestroy( $this->_im );
  }

  public function getCode() :string {
    return $this->_code;
  }

  public function getImage() :void {
    header( 'Content-type: image/gif' );
    imagegif( $this->_im );
  }

};

$captcha->getImage();
