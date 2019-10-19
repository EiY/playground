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

  public function __construct( $width = 80, $height = 30, $size = 4, $map = '234678qwertyuipkjhfdazxcvbnm' ) {

    $this->_im = $im = imagecreatetruecolor( $width, $height );

    imagefilledrectangle( $im, 1, 1, $width - 2, $height - 2, 
      imagecolorallocate( $im, 255, 255, 255 ) );
  
    $color = imagecolorallocate( $im, mt_rand( 50, 90 ), mt_rand( 80, 200 ), mt_rand( 90, 180 ) );
    $fonts = [ 'HelveticaNeueLTPro-Bd.otf', 'HelveticaNeueLTPro-Roman.otf' ];
  
    for( 
      $i = 0, $this->_code = '', $length = strlen( $map ) - 1, $font_length = count( $fonts ) - 1;
      $fs = mt_rand( 14, 20 ), $char = $map[mt_rand( 0, $length )], $i < $size; 
      ++ $i, $this->_code .= $char
    ) imagettftext( $im, $fs, mt_rand( -15, 15 ), 5 + $i * $fs, mt_rand( 20, 26 ), $color, $fonts[mt_rand( 0, $font_length )], $char );
  
    for ( $i = 0; $i < 30; ++ $i )
      imagesetpixel( $im, mt_rand( 1, $width - 2 ), mt_rand( 1, $height - 2 ), $color );
  
    for ( $i = 0; $i < 6; ++ $i )
      imageline( $im, mt_rand( 1, $width - 2 ), mt_rand( 1, $height - 2 ), mt_rand( 1, $width - 2 ), mt_rand( 1, $height - 2 ), $color );
  
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
