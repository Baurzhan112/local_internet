<?php

class Chess
{
	public $board_array;
	public $config;
	public $onAddFigure;

	function __construct() 
	{
		$this->board_array = array(
			'1'=>array('a'=>' ','b'=>' ','c'=>' ','d'=>' ','e'=>' ','f'=>' ','g'=>' ','h'=>' ',),			
			'2'=>array('a'=>' ','b'=>' ','c'=>' ','d'=>' ','e'=>' ','f'=>' ','g'=>' ','h'=>' ',),			
			'3'=>array('a'=>' ','b'=>' ','c'=>' ','d'=>' ','e'=>' ','f'=>' ','g'=>' ','h'=>' ',),			
			'4'=>array('a'=>' ','b'=>' ','c'=>' ','d'=>' ','e'=>' ','f'=>' ','g'=>' ','h'=>' ',),		
			'5'=>array('a'=>' ','b'=>' ','c'=>' ','d'=>' ','e'=>' ','f'=>' ','g'=>' ','h'=>' ',),			
			'6'=>array('a'=>' ','b'=>' ','c'=>' ','d'=>' ','e'=>' ','f'=>' ','g'=>' ','h'=>' ',),			
			'7'=>array('a'=>' ','b'=>' ','c'=>' ','d'=>' ','e'=>' ','f'=>' ','g'=>' ','h'=>' ',),			
			'8'=>array('a'=>' ','b'=>' ','c'=>' ','d'=>' ','e'=>' ','f'=>' ','g'=>' ','h'=>' ',)			
			);
		$this->config = [
		      'figures' => [
		            'пешка' => 'o',
		            'ладья' => 'x',
		         ],
		      'symbols' => ['a','b','c','d','e','f','g','h'],
		      'keys' => ['1','2','3','4','5','6','7','8'],
		   	];
   }

   public function startGame()
   {
   		print('> Выберите действие:'.PHP_EOL);
   		print('1. Добавить фигуру'.PHP_EOL);
   		print('2. Удалить фигуру'.PHP_EOL);
   		print('3. Переместить фигуру'.PHP_EOL);
   		print('4. Добавить новую фигуру'.PHP_EOL);
   		print('5. Сохранить доску'.PHP_EOL);   		
   		print('6. Загрузить доску'.PHP_EOL);   		
   		$handle = fopen ("php://stdin","r");
		$line = fgets($handle);
		$line = trim($line);
		switch ($line) {
			case '1':
				$this->addFigure();
				break;
			case '2':
				$this->removeFigure();
				break;
			case '3':
				$this->moveFigure();
				break;
			case '4':
				$this->addNewFigure();
				break;	
			case '5':
				$this->saveBoard();
				break;		
			case '6':
				$this->loadBoard();
				break;		
			default:
				print('Выберите одно из 6-х действий'.PHP_EOL);
				$this->startGame();
				break;
		}	
   }

   public function showBoard()
   {
   		print('    |a|b|c|d|e|f|g|h|');
   		print(PHP_EOL);
   		$counter = 0;;
   		foreach ($this->board_array as $key => $value) {
   			$counter++;
   			print('   '.$counter.'|');
   			foreach ($value as $symbol => $val) {
   				print($val.'|');
   			}
   			print(PHP_EOL);
   		}
   		print(PHP_EOL);
   }
   
   public function addNewFigure()
   {
   		print('Добавление новой фигуры:'.PHP_EOL.'Введите название для новой фигуры:'.PHP_EOL);
   		$figure_name = $this->getFigureName();
   		$validate_fname = $this->validateFigureName($figure_name);
   		if ($validate_fname)
   		{
   			print('Введите обозначение для новой фигуры:'.PHP_EOL);
   			$figure_icon = $this->getFigureIcon();
   			$validate_ficon = $this->validateFigureIcon($figure_icon);
   			if ($validate_ficon)
   			{
   				$this->config['figures'] += array($figure_name => $figure_icon);
   				print('Новая фигура "'.$figure_name.'" успешно добавлена!'.PHP_EOL);
   				$this->showBoard();
   				$this->startGame();
   			}
   			else
   			{
   				print('Не введено обозначение для фигуры!'.PHP_EOL);
   				$this->addNewFigure();
   			}
   		}
   		else $this->addNewFigure();
   }

   public function addFigure()
   {
   		print('Добавление фигуры'.PHP_EOL);
   		$figure_icon = $this->chooseFigure();
   		$isset_figure = $this->issetFigure($figure_icon);
   		if ($isset_figure)
	   	{	
	   		print('Выберите позицию для добавления, например d5:'.PHP_EOL);
	   		$position = $this->choosePosition();
	   		$validate_position = $this->validatePosition($position);
	   		if ($validate_position)
	   		{
	   			$isempty_position = $this->isemptyPosition($position);
	   			if ($isempty_position)
	   			{
	   				if(is_callable($this->onAddFigure)){
				        $this->onAddFigure();
				    }
	   				$symbol = $position[0];
	   				$key = $position[1];
	   				$this->board_array[$key][$symbol] = $figure_icon;
	   				print('Фигура успешно добавлена'.PHP_EOL);
	   				$this->showBoard();
	   				$this->startGame();
	   			}
	   			else
	   			{
	   				print('Указанная позиция не свободна!'.PHP_EOL);
	   				$this->addFigure();
	   			}
	   		}
	   		else
	   		{
	   			print('Не корректно введена позиция!'.PHP_EOL);
	   			$this->addFigure();
	   		}
	   	}
	   	else $this->addFigure();
   }

   public function removeFigure()
   {
   		print('Удаление фигуры'.PHP_EOL.'Выберите позицию для удаления фигуры:'.PHP_EOL);	
   		$position = $this->choosePosition();
   		$validate_position = $this->validatePosition($position);
   		if ($validate_position)
   		{
   			$isempty_position = $this->isemptyPosition($position);
   			if (!$isempty_position)
   			{
   				$symbol = $position[0];
   				$key = $position[1];
   				$this->board_array[$key][$symbol] = ' ';
   				print('Фигура успешно удалена'.PHP_EOL);
   				$this->showBoard();
   				$this->startGame();
   			}
   			else
   			{
   				print('На указанной позиции нет никакой фигуры!'.PHP_EOL);
   				$this->removeFigure();
   			}
   		}
   		else
   		{
   			print('Не корректно введена позиция!'.PHP_EOL);
   			$this->removeFigure();
   		}
   }

   public function moveFigure()
   {
   		print('Перемещение фигуры'.PHP_EOL.'Выберите позицию откуда переместить:'.PHP_EOL);	
   		$first_position = $this->choosePosition();
   		$validate_position = $this->validatePosition($first_position);
   		if ($validate_position)
   		{
   			$isempty_position = $this->isemptyPosition($first_position);
   			if (!$isempty_position)
   			{
   				print('Выберите позицию куда переместить:'.PHP_EOL);
   				$second_position = $this->choosePosition();
   				$validate_position = $this->validatePosition($second_position);
   				if ($validate_position)
   				{
   					$isempty_position = $this->isemptyPosition($second_position);
   					if ($isempty_position)
   					{
   						$f_pos_sym = $first_position[0];
				   		$f_pos_key = $first_position[1];
				   		$s_pos_sym = $second_position[0];
				   		$s_pos_key = $second_position[1];
				   		$this->board_array[$s_pos_key][$s_pos_sym] = $this->board_array[$f_pos_key][$f_pos_sym];
				   		$this->board_array[$f_pos_key][$f_pos_sym] = ' ';
				   		print('Фигура успешно перемещена!'.PHP_EOL);
				   		$this->showBoard();
				   		$this->startGame();
   					}
   					else
   					{
   						print('Указанная позиция занята другой фигурой!'.PHP_EOL);
   						$this->moveFigure();
   					}
   				}	
   				else
   				{
   					print('Не корректно введена позиция!'.PHP_EOL);
   					$this->moveFigure();
   				}
   			}
   			else
   			{
   				print('На указанной позиции нет никакой фигуры!'.PHP_EOL);
   				$this->moveFigure();
   			}
   		}
   		else
   		{
   			print('Не корректно введена позиция!'.PHP_EOL);
   			$this->moveFigure();
   		}
   }

   public function saveBoard()
   {
   		file_put_contents("board.json", json_encode($this->board_array));
   		print('Текущий вид доски сохранен!'.PHP_EOL);
   		$this->showBoard();
   		$this->startGame();
   }

   public function loadBoard()
   {
   		$this->board_array = json_decode(file_get_contents("board.json"), true);
   		print('Загружен последний сохраненный вид доски'.PHP_EOL);
   		$this->showBoard();
   		$this->startGame();
   }

   public function getFigureName()
   {
   		$handle = fopen ("php://stdin","r");
		$figure_name = fgets($handle);
		$figure_name = trim($figure_name);
		return $figure_name;
   }

   public function getFigureIcon()
   {
   		$handle = fopen ("php://stdin","r");
		$figure_icon = fgets($handle);
		$figure_icon = trim($figure_icon);
		return $figure_icon;
   }

   public function validateFigureName($figure_name)
   {
   		$result = false;
   		if (strlen($figure_name) > 0) $result = true;
   		return $result;
   }

   public function validateFigureIcon($figure_icon)
   {
   		$result = false;
   		if (strlen($figure_icon) > 0) $result = true;
   		return $result;
   }

   public function choosePosition()
   {	
   		$handle = fopen ("php://stdin","r");
		$position = fgets($handle);
		$position = trim($position);
		return $position;
   }

   public function chooseFigure()
   {
   		print('Выберите фигуру из списка: '.PHP_EOL);
   		foreach ($this->config['figures'] as $key => $value) {
   			print($key.': '.$value.PHP_EOL);
   		}
   		$handle = fopen ("php://stdin","r");
		$figure_icon = fgets($handle);
		$figure_icon = trim($figure_icon);
		return $figure_icon;
   }

   public function issetFigure($figure_icon)
   {
   		$result = false;
   		foreach ($this->config['figures'] as $key => $value) {
   			if ($figure_icon == $value)
   			{
   				$result = true;
   				break;
   			}
   		}
   		return $result;
   }

   public function validatePosition($position)
   {
   		$result = false;
   		if (strlen($position) == 2)
		{
			$position_symbol = $position[0];
			$position_key = $position[1];
			if (in_array($position_symbol, $this->config['symbols']) and in_array($position_key, $this->config['keys']))
			{
				$result = true;
			}
		}
		return $result;
   }

   public function isemptyPosition($position)
   {
   		$result = false;
   		$key = $position[1];
   		$symbol = $position[0];
   		if ($this->board_array[$key][$symbol] == ' ') $result = true;
   		return $result;
   }

}

$game = new Chess();
$game->showBoard();
$game->startGame();