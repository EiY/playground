<?php
interface captcha {
    public function __construct( $width, $height, $size, $map );
    public function __destruct();
    public function getCode();
    public function getImage();
}

$captcha = new class( 160, 50 ) implements captcha {
  private $_im;
  private $_code;

  public function __construct( 
    $width = 120, $height = 30, $size = 5, $map = '234678qwertyuipkjhfdazxcvbnm'
    ) {

    for ( $i = 0, $this->_code = '', $length = strlen( $map ) - 1; $i < $size; ++ $i )
      $this->_code .= $map[mt_rand( 0, $length )];

    $this->_im = $im = imagecreate( $width, $height );

    imagecolorallocate( $im, mt_rand( 200, 255 ) , mt_rand( 200, 255 ) , mt_rand( 200, 255 ) );

    for ( $i = 0, $y = floor( $height / 2 ) + floor( $height / 4 ), $fs = mt_rand( 30, 35 ); $i < $size; ++ $i )
      imagettftext( $im, $fs, mt_rand( -30, 30 ), floor( $width / $size ) * $i + 8, $y, imagecolorallocate( 
        $im, mt_rand(0, 50) , mt_rand(50, 100) , mt_rand(100, 140)
      ), 'AdobeGothicStd-Bold.otf', $this->_code[$i] );

    for ($i = floor( $height * 2 ); $i > 0; -- $i )
      imagettftext( $im, rand( 8, 15 ), rand(0, 360), mt_rand( 0, $width ), mt_rand( 0, $height ), imagecolorallocate(
        $im, mt_rand( 40, 140 ), mt_rand( 40, 140 ), mt_rand( 40, 140 )
      ), 'TektonPro-BoldCond.otf', $map[mt_rand( 0, $length )] );

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
