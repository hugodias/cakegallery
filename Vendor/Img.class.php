<?php

class Img {


	private $origem, $img;
	private $largura, $altura, $nova_largura, $nova_altura, $tamanho_html;
	private $formato, $extensao, $tamanho, $arquivo, $diretorio;
	private $extensoes_validas;
	private $rgb;
	private $erro;

	public function __construct($origem = '', $extensoes_validas = array('jpg', 'jpeg', 'jpe', 'gif', 'bmp', 'png')) {

		$this->origem = $origem;
		$this->img = '';
		$this->largura = 0;
		$this->altura = 0;
		$this->nova_largura = 0;
		$this->nova_altura = 0;
		$this->formato = 0;
		$this->extensao = '';
		$this->tamanho = '';
		$this->extensoes_validas = $extensoes_validas;
		$this->arquivo = '';
		$this->diretorio = '';
		$this->rgb = array(255, 255, 255);
		$this->tamanho_html = '';

		if ($this->origem) {
			$this->dados();
		}

	}

	private function dados() {
		$this->erro = 'OK';

		if (!is_file($this->origem)) {
			$this->erro = 'Erro: Arquivo de imagem não encontrado!';
		} else {
			$this->dadosArquivo();

			if (!$this->eImagem()) {
				$this->erro = 'Erro: Arquivo ' . $this->origem . ' não é uma imagem!';
			} else {
				$this->dimensoes();
				$this->criaImagem();
			}
		}

	}

	/**
	 * Retorna valida��o da imagem
	 * @param
	 * @return String string com erro de mensagem ou 'OK' para imagem v�lida
	 */
	public function valida() {
		return $this->erro;
	} // fim valida

	/**
	 * Carrega uma nova imagem, fora do construtor
	 * @param String caminho da imagem a ser carregada
	 * @return void
	 */
	public function carrega($origem = '') {
		$this->origem = $origem;
		$this->dados();
	} // fim carrega

//------------------------------------------------------------------------------
// dados da imagem

	/**
	 * Busca dimens�es e formato real da imagem
	 * @param
	 * @return void
	 */
	private function dimensoes() {
		$dimensoes = getimagesize($this->origem);
		$this->largura = $dimensoes[0];
		$this->altura = $dimensoes[1];
		// 1 = gif, 2 = jpeg, 3 = png, 6 = BMP
		// http://br2.php.net/manual/en/function.exif-imagetype.php
		$this->formato = $dimensoes[2];
		$this->tamanho_html = $dimensoes[3];
	} // fim dimensoes

	/**
	 * Busca dados do arquivo
	 * @param
	 * @return void
	 */
	private function dadosArquivo() {
		// imagem de origem
		$pathinfo = pathinfo($this->origem);
		$this->extensao = strtolower($pathinfo['extension']);
		$this->arquivo = $pathinfo['basename'];
		$this->diretorio = $pathinfo['dirname'];
		$this->tamanho = filesize($this->origem);
	} // fim dadosArquivo

	/**
	 * Verifica se o arquivo indicado � uma imagem
	 * @param
	 * @return Boolean true/false
	 */
	private function eImagem() {
		// filtra extens�o
		if (!in_array($this->extensao, $this->extensoes_validas)) {
			return false;
		} else {
			return true;
		}
	} // fim validaImagem	

//------------------------------------------------------------------------------
// manipula��o da imagem	

	/**
	 * Cria objeto de imagem para manipula��o no GD
	 * @param
	 * @return void
	 */
	private function criaImagem() {
		switch ($this->formato) {
			case 1:
				$this->img = imagecreatefromgif($this->origem);
				$this->extensao = 'gif';
				break;
			case 2:
				$this->img = imagecreatefromjpeg($this->origem);
				$this->extensao = 'jpg';
				break;
			case 3:
				$this->img = imagecreatefrompng($this->origem);
				$this->extensao = 'png';
				break;
			case 6:
				$this->img = imagecreatefrombmp($this->origem);
				$this->extensao = 'bmp';
				break;
			default:
				trigger_error('Arquivo inv�lido!', E_USER_WARNING);
				break;
		}
	} // fim criaImagem

//------------------------------------------------------------------------------
// fun��es para redimensionamento

	/**
	 * Redimensiona imagem
	 * @param Int $nova_largura valor em pixels da nova largura da imagem
	 * @param Int $nova_altura valor em pixels da nova altura da imagem
	 * @param String $tipo m�todo para redimensionamento (padr�o [vazio], 'fill' [preenchimento] ou 'crop')
	 * @return Boolean/void
	 */
	public function redimensiona($nova_largura = 0, $nova_altura = 0, $tipo = '', $rgb = array(255, 255, 255)) {

		// seta vari�veis passadas via par�metro
		$this->nova_largura = $nova_largura;
		$this->nova_altura = $nova_altura;
		$this->rgb = $rgb;

		// define se s� passou nova largura ou altura
		if (!$this->nova_largura && !$this->nova_altura) {
			return false;
		} // s� passou altura
		elseif (!$this->nova_largura) {
			$this->nova_largura = $this->largura / ($this->altura / $this->nova_altura);
		} // s� passou largura
		elseif (!$this->nova_altura) {
			$this->nova_altura = $this->altura / ($this->largura / $this->nova_largura);
		}

		// redimensiona de acordo com tipo
		if ('crop' == $tipo) {
			$this->resizeCrop();
		} elseif ('fill' == $tipo) {
			$this->resizeFill();
		} elseif ('proportional_resize' == $tipo) {
			if ($this->nova_altura && $this->nova_largura) {

				if ($this->altura > $this->largura) {
					$this->nova_largura = $this->largura / ($this->altura / $this->nova_altura);
				} else if ($this->largura > $this->altura) {
					$this->nova_altura = $this->altura / ($this->largura / $this->nova_largura);
				}
			}

			$this->resize();
		} else {
			$this->resize();
		}

		// atualiza dimens�es da imagem
		$this->altura = $this->nova_altura;
		$this->largura = $this->nova_largura;

	} // fim redimensiona

	/**
	 * Redimensiona imagem, modo padr�o, sem crop ou fill (distorcendo)
	 * @param
	 * @return void
	 */
	private function resize() {
		// cria imagem de destino tempor�ria
		$imgtemp = imagecreatetruecolor($this->nova_largura, $this->nova_altura);

		imagecopyresampled($imgtemp, $this->img, 0, 0, 0, 0, $this->nova_largura, $this->nova_altura, $this->largura, $this->altura);
		$this->img = $imgtemp;

	} // fim resize()

	/**
	 * Redimensiona imagem sem cropar, proporcionalmente,
	 * preenchendo espa�o vazio com cor rgb especificada
	 * @param
	 * @return void
	 */
	private function resizeFill() {
		// cria imagem de destino tempor�ria
		$imgtemp = imagecreatetruecolor($this->nova_largura, $this->nova_altura);

		// adiciona cor de fundo � nova imagem
		$corfundo = imagecolorallocate($imgtemp, $this->rgb[0], $this->rgb[1], $this->rgb[2]);
		imagefill($imgtemp, 0, 0, $corfundo);

		// salva vari�veis para centraliza��o
		$dif_y = $this->nova_altura;
		$dif_x = $this->nova_largura;

		// verifica altura e largura
		if ($this->largura > $this->altura) {
			$this->nova_altura = (($this->altura * $this->nova_largura) / $this->largura);
		} elseif ($this->largura <= $this->altura) {
			$this->nova_largura = (($this->largura * $this->nova_altura) / $this->altura);
		} // fim do if verifica altura largura

		// copia com o novo tamanho, centralizando
		$dif_x = ($dif_x - $this->nova_largura) / 2;
		$dif_y = ($dif_y - $this->nova_altura) / 2;
		imagecopyresampled($imgtemp, $this->img, $dif_x, $dif_y, 0, 0, $this->nova_largura, $this->nova_altura, $this->largura, $this->altura);
		$this->img = $imgtemp;
	} // fim resizeFill()

	/**
	 * Redimensiona imagem, cropando para encaixar no novo tamanho, sem sobras
	 * baseado no script original de Noah Winecoff
	 * http://www.findmotive.com/2006/12/13/php-crop-image/
	 * @return void
	 */
	private function resizeCrop() {
		// cria imagem de destino tempor�ria
		$imgtemp = imagecreatetruecolor($this->nova_largura, $this->nova_altura);

		// m�dia altura/largura
		$hm = $this->altura / $this->nova_altura;
		$wm = $this->largura / $this->nova_largura;

		// 50% para c�lculo do crop
		$h_height = $this->nova_altura / 2;
		$h_width = $this->nova_largura / 2;

		// largura > altura
		if ($wm > $hm) {
			$adjusted_width = $this->largura / $hm;
			$half_width = $adjusted_width / 2;
			$int_width = $half_width - $h_width;
			imagecopyresampled($imgtemp, $this->img, -$int_width, 0, 0, 0, $adjusted_width, $this->nova_altura, $this->largura, $this->altura);
		} // largura <= altura
		elseif (($wm <= $hm)) {
			$adjusted_height = $this->altura / $wm;
			$half_height = $adjusted_height / 2;
			$int_height = $half_height - $h_height;
			imagecopyresampled($imgtemp, $this->img, 0, -$int_height, 0, 0, $this->nova_largura, $adjusted_height, $this->largura, $this->altura);
		}

		$this->img = $imgtemp;
	} // fim resizeCrop


//------------------------------------------------------------------------------
// fun��es de manipula��o da imagem

	/**
	 * flipa/inverte imagem
	 * baseado no script original de Noah Winecoff
	 * http://www.php.net/manual/en/ref.image.php#62029
	 * @param String $tipo tipo de espelhamento: h - horizontal, v - vertical
	 * @return void
	 */
	public function flip($tipo = 'h') {
		$w = imagesx($this->img);
		$h = imagesy($this->img);

		$imgtemp = imagecreatetruecolor($w, $h);

		// vertical
		if ('v' == $tipo) {
			for ($y = 0; $y < $h; $y++) {
				imagecopy($imgtemp, $this->img, 0, $y, 0, $h - $y - 1, $w, 1);
			}
		}

		// horizontal
		if ('h' == $tipo) {
			for ($x = 0; $x < $w; $x++) {
				imagecopy($imgtemp, $this->img, $x, 0, $w - $x - 1, 0, 1, $h);
			}
		}

		$this->img = $imgtemp;

	} // fim flip

	/**
	 * gira imagem
	 * @param Int $graus grau para giro
	 * @param Array $rgb cor RGB para preenchimento
	 * @return void
	 */
	public function girar($graus, $rgb = array(255, 255, 255)) {
		$corfundo = imagecolorallocate($this->img, $rgb[0], $rgb[1], $rgb[2]);
		$this->img = imagerotate($this->img, $graus, $corfundo);
	} // fim girar

	/**
	 * adiciona texto � imagem
	 * @param String $texto texto a ser inserido
	 * @param Int $tamanho tamanho da fonte
	 * @param Int $x posi��o x do texto na imagem
	 * @param Int $y posi��o y do texto na imagem
	 * @param Array $rgb cor do texto
	 * @param Boolean $truetype true para utilizar fonte truetype, false para fonte do sistema
	 * @param String $fonte nome da fonte truetype a ser utilizada
	 * @return void
	 */
	public function legenda($texto, $tamanho = 10, $x = 0, $y = 0, $rgb = array(255, 255, 255), $truetype = false, $fonte = '') {
		$cortexto = imagecolorallocate($this->img, $rgb[0], $rgb[1], $rgb[2]);

		// truetype ou fonte do sistema?
		if ($truetype === true) {
			imagettftext($this->img, $tamanho, 0, $x, $y, $cortexto, $fonte, $texto);
		} else {
			imagestring($this->img, $tamanho, $x, $y, $texto, $cortexto);
		}
	} // fim legenda

	/**
	 * adiciona imagem de marca d'�gua
	 * @param String $imagem caminho da imagem de marca d'�gua
	 * @param Int $x posi��o x da marca na imagem
	 * @param Int $y posi��o y da marca na imagem
	 * @return Boolean true/false dependendo do resultado da opera��o
	 * @param Int $alfa valor para transpar�ncia (0-100)
	 * -> se utilizar alfa, a fun��o imagecopymerge n�o preserva
	 * -> o alfa nativo do PNG
	 */
	public function marca($imagem, $x = 0, $y = 0, $alfa = 100) {
		// cria imagem tempor�ria para merge
		if ($imagem) {
			$pathinfo = pathinfo($imagem);
			switch (strtolower($pathinfo['extension'])) {
				case 'jpg':
				case 'jpeg':
					$marcadagua = imagecreatefromjpeg($imagem);
					break;
				case 'png':
					$marcadagua = imagecreatefrompng($imagem);
					break;
				case 'gif':
					$marcadagua = imagecreatefromgif($imagem);
					break;
				case 'bmp':
					$marcadagua = imagecreatefrombmp($imagem);
					break;
				default:
					$this->erro = 'Arquivo de marca d\'�gua inv�lido.';
					return false;
			}
		} else {
			return false;
		}
		// dimens�es
		$marca_w = imagesx($marcadagua);
		$marca_h = imagesy($marcadagua);
		// retorna imagens com marca d'�gua
		if (is_numeric($alfa) && (($alfa > 0) && ($alfa < 100))) {
			imagecopymerge($this->img, $marcadagua, $x, $y, 0, 0, $marca_w, $marca_h, $alfa);
		} else {
			imagecopy($this->img, $marcadagua, $x, $y, 0, 0, $marca_w, $marca_h);
		}
		return true;
	} // fim marca

	/**
	 * adiciona imagem de marca d'�gua, com valores fixos
	 * ex: topo_esquerda, topo_direita etc.
	 * Implementa��o original por Giolvani <inavloig@gmail.com>
	 * @param String $imagem caminho da imagem de marca d'�gua
	 * @param String $posicao posi��o/orienta��o fixa da marca d'�gua
	 *        [topo, meio, baixo] + [esquerda, centro, direita]
	 * @param Int $alfa valor para transpar�ncia (0-100)
	 * @return void
	 */
	public function marcaFixa($imagem, $posicao, $alfa = 100) {

		// dimens�es da marca d'�gua
		list($marca_w, $marca_h) = getimagesize($imagem);

		// define X e Y para posicionamento
		switch ($posicao) {
			case 'topo_esquerda':
				$x = 0;
				$y = 0;
				break;
			case 'topo_centro':
				$x = ($this->largura - $marca_w) / 2;
				$y = 0;
				break;
			case 'topo_direita':
				$x = $this->largura - $marca_w;
				$y = 0;
				break;
			case 'meio_esquerda':
				$x = 0;
				$y = ($this->altura / 2) - ($marca_h / 2);
				break;
			case 'meio_centro':
				$x = ($this->largura - $marca_w) / 2;
				$y = ($this->altura / 2) - ($marca_h / 2);
				break;
			case 'meio_direita':
				$x = $this->largura - $marca_w;
				$y = ($this->altura / 2) - ($marca_h / 2);
				break;
			case 'baixo_esquerda':
				$x = 0;
				$y = $this->altura - $marca_h;
				break;
			case 'baixo_centro':
				$x = ($this->largura - $marca_w) / 2;
				$y = $this->altura - $marca_h;
				break;
			case 'baixo_direita':
				$x = $this->largura - $marca_w;
				$y = $this->altura - $marca_h;
				break;
			default:
				return false;
				break;
		} // end switch posicao

		// cria marca
		$this->marca($imagem, $x, $y, $alfa);

	} // fim marcaFixa


//------------------------------------------------------------------------------
// gera imagem de sa�da

	/**
	 * retorna sa�da para tela ou arquivo
	 * @param String $destino caminho e nome do arquivo a serem criados
	 * @param Boolean $salvar salva imagem ou n�o
	 * @param Int $qualidade qualidade da imagem no caso de JPEG (0-100)
	 * @return void
	 */
	public function grava($destino = '', $qualidade = 100, $convertpng2jpg = false) {
		// dados do arquivo de destino	
		if ($destino) {
			$pathinfo = pathinfo($destino);
			$dir_destino = $pathinfo['dirname'];
			$extensao_destino = strtolower($pathinfo['extension']);

			// valida diret�rio
			if (!is_dir($dir_destino)) {
				$this->erro = 'Diret�rio de destino inv�lido ou inexistente';
				return false;
			}

		}

		// valida extens�o de destino
		if (!isset($extensao_destino)) {
			$extensao_destino = $this->extensao;
		} else {
			if (!in_array($extensao_destino, $this->extensoes_validas)) {
				$this->erro = 'Extens�o inv�lida para o arquivo de destino';
				return false;
			}
		}


		switch ($extensao_destino) {
			case 'jpg':
			case 'jpeg':
			case 'bmp':
				if ($destino) {
					imagejpeg($this->img, $destino, $qualidade);
				} else {
					header("Content-type: image/jpeg");
					imagejpeg($this->img, NULL, $qualidade);
					imagedestroy($this->img);
					exit;
				}
				break;
			case 'png':
				if ($convertpng2jpg) {
					# Convert from PNG to JPG
					$destino = $this->png2jpg();

				} else {
					if ($destino) {
						imagepng($this->img, $destino);
					} else {
						header("Content-type: image/png");
						imagepng($this->img);
						imagedestroy($this->img);
						exit;
					}
				}
				break;
			case 'gif':
				if ($destino) {
					imagegif($this->img, $destino);
				} else {
					header("Content-type: image/gif");
					imagegif($this->img);
					imagedestroy($this->img);
					exit;
				}
				break;
			default:
				return false;
				break;
		}

		return $destino;
	} // fim grava

	public function getAltura() {
		return $this->altura;
	}

	public function getLargura() {
		return $this->largura;
	}

	public function png2jpg($quality = 100) {

		if (strpos($this->origem, ".png")) {
			$output = str_replace(".png", ".jpg", $this->origem);

			imagejpeg($this->img, $output, $quality);

			if (file_exists($this->origem)) {
				# Change permissions before delete it
				chmod($this->origem, 0755);
				# Delete the PNG file
				unlink($this->origem);
			}

			# Return the JPG file path
			$this->origem = $output;

			return $this->origem;
		}
	}

//------------------------------------------------------------------------------
// fim da classe    
}

//------------------------------------------------------------------------------
// suporte para a manipula��o de arquivos BMP

/*********************************************/
/* Function: ImageCreateFromBMP              */
/* Author:   DHKold                          */
/* Contact:  admin@dhkold.com                */
/* Date:     The 15th of June 2005           */
/* Version:  2.0B                            */
/*********************************************/

function imagecreatefrombmp($filename) {
	//Ouverture du fichier en mode binaire
	if (!$f1 = fopen($filename, "rb")) return FALSE;

	//1 : Chargement des ent?tes FICHIER
	$FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1, 14));
	if ($FILE['file_type'] != 19778) return FALSE;

	//2 : Chargement des ent?tes BMP
	$BMP = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel' .
		'/Vcompression/Vsize_bitmap/Vhoriz_resolution' .
		'/Vvert_resolution/Vcolors_used/Vcolors_important', fread($f1, 40));
	$BMP['colors'] = pow(2, $BMP['bits_per_pixel']);
	if ($BMP['size_bitmap'] == 0) $BMP['size_bitmap'] = $FILE['file_size'] - $FILE['bitmap_offset'];
	$BMP['bytes_per_pixel'] = $BMP['bits_per_pixel'] / 8;
	$BMP['bytes_per_pixel2'] = ceil($BMP['bytes_per_pixel']);
	$BMP['decal'] = ($BMP['width'] * $BMP['bytes_per_pixel'] / 4);
	$BMP['decal'] -= floor($BMP['width'] * $BMP['bytes_per_pixel'] / 4);
	$BMP['decal'] = 4 - (4 * $BMP['decal']);
	if ($BMP['decal'] == 4) $BMP['decal'] = 0;

	//3 : Chargement des couleurs de la palette
	$PALETTE = array();
	if ($BMP['colors'] < 16777216) {
		$PALETTE = unpack('V' . $BMP['colors'], fread($f1, $BMP['colors'] * 4));
	}

	//4 : Cr?ation de l'image
	$IMG = fread($f1, $BMP['size_bitmap']);
	$VIDE = chr(0);

	$res = imagecreatetruecolor($BMP['width'], $BMP['height']);
	$P = 0;
	$Y = $BMP['height'] - 1;
	while ($Y >= 0) {
		$X = 0;
		while ($X < $BMP['width']) {
			if ($BMP['bits_per_pixel'] == 24)
				$COLOR = @unpack("V", substr($IMG, $P, 3) . $VIDE);
			elseif ($BMP['bits_per_pixel'] == 16) {
				$COLOR = @unpack("n", substr($IMG, $P, 2));
				$COLOR[1] = $PALETTE[$COLOR[1] + 1];
			} elseif ($BMP['bits_per_pixel'] == 8) {
				$COLOR = @unpack("n", $VIDE . substr($IMG, $P, 1));
				$COLOR[1] = $PALETTE[$COLOR[1] + 1];
			} elseif ($BMP['bits_per_pixel'] == 4) {
				$COLOR = @unpack("n", $VIDE . substr($IMG, floor($P), 1));
				if (($P * 2) % 2 == 0) $COLOR[1] = ($COLOR[1] >> 4); else $COLOR[1] = ($COLOR[1] & 0x0F);
				$COLOR[1] = $PALETTE[$COLOR[1] + 1];
			} elseif ($BMP['bits_per_pixel'] == 1) {
				$COLOR = @unpack("n", $VIDE . substr($IMG, floor($P), 1));
				if (($P * 8) % 8 == 0) $COLOR[1] = $COLOR[1] >> 7;
				elseif (($P * 8) % 8 == 1) $COLOR[1] = ($COLOR[1] & 0x40) >> 6;
				elseif (($P * 8) % 8 == 2) $COLOR[1] = ($COLOR[1] & 0x20) >> 5;
				elseif (($P * 8) % 8 == 3) $COLOR[1] = ($COLOR[1] & 0x10) >> 4;
				elseif (($P * 8) % 8 == 4) $COLOR[1] = ($COLOR[1] & 0x8) >> 3;
				elseif (($P * 8) % 8 == 5) $COLOR[1] = ($COLOR[1] & 0x4) >> 2;
				elseif (($P * 8) % 8 == 6) $COLOR[1] = ($COLOR[1] & 0x2) >> 1;
				elseif (($P * 8) % 8 == 7) $COLOR[1] = ($COLOR[1] & 0x1);
				$COLOR[1] = $PALETTE[$COLOR[1] + 1];
			} else
				return FALSE;
			imagesetpixel($res, $X, $Y, $COLOR[1]);
			$X++;
			$P += $BMP['bytes_per_pixel'];
		}
		$Y--;
		$P += $BMP['decal'];
	}

	//Fermeture du fichier
	fclose($f1);

	return $res;

} // fim function image from BMP
