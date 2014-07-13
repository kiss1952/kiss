<?php
/**
  * User:温永
  * Date:2014/7/8
  * @desc:simplexml生成xml类
  *
*/
class GenerateXml
{	
	const ROOT = '<root />';

	//数组中标签数组
	const  TAG = 'tag';
	
	//数组中标签的属性
	const  PROPERTY = 'property';
	
	//数组中标签的子标签
	const  CHILD = 'children';
	
	//数组中代表名字
	const  TEXT = 'text';
	
	//数组中代表值
	const  VALUE = 'value';

	//xml数组，根据此数组格式生成xml
	public $xmlArray;
	
	public function __construct()
	{
		if ($this->xmlArray) {
			 $this->xmlArray = array();
		}
	}

	public function run()
	{
		if (count($this->xmlArray) == 0) {
			echo "xmlArray没有传入数据";
		} else {
			self::generateData();
		}
	}
	
	protected function generateData()
	{	
		$data = self::exitData($this->xmlArray);
		$xml = new SimpleXMLElement(self::ROOT);
		$tag = $xml->addChild($data[self::TAG]);
		
		foreach ($data[self::PROPERTY] as $Property) {
			$tag->addAttribute($Property[self::TEXT],$Property[self::VALUE]);
		}

		$this->childrenGetData($tag, $data[self::CHILD]);
		echo $xml->asXML();
	}
	
	protected function childrenGetData($tag, $data)
	{	
		if (!empty($data)) {
			foreach ($data as $children) {
				$childrenData = self::exitData($children);
				$childrenTag = $tag->addChild($childrenData[self::TAG]);
				foreach ($childrenData[self::PROPERTY] as $Property) {
					$childrenTag->addAttribute($Property[self::TEXT],$Property[self::VALUE]);
				}

				$children = $this->arrayExists(self::CHILD, $children);
				$this->childrenGetData($childrenTag, $children);
			}
		}
	}

	protected function exitData($array)
	{	
		$Tag = $this->arrayExists(self::TAG, $array);
		$Property = $this->arrayExists(self::PROPERTY, $array);
		$Child = $this->arrayExists(self::CHILD, $array);
		$arr = Array(self::TAG=>$Tag,self::PROPERTY=>$Property,self::CHILD=>$Child);
		return $arr;
	}
	
	public function arrayExists($value, $arr)
	{
		if (array_key_exists($value, $arr)) {
			$result = $arr[$value]?$arr[$value]:"";
			
		} else {
			$result = "";
		}

		return $result;
	}
}

$arr = array(
				"tag" => "Tag",
				"property" =>  array(
						array(
							"text" => "Property",
							"value" => "Property"
						)
				),
				"children" => array(
					array(
						"tag" => "childrenTag",
						"property" =>  array(
							array(
								"text" => "childrenProperty",
								"value" => "childrenProperty"
							)
						),
						"children" => array(
							array(
								"tag" => "Ta1g",
								"property" =>  array(
									array(
										"text" => "Property1",
										"value" => "Property1"
									)
								 )
							)
						)
					),
					array(
						"tag" => "childrenTag2",
						"property" =>  array(
							array(
								"text" => "childrenProperty",
								"value" => "childrenProperty"
							)
						),
						"children" => array(
							array(
								"tag" => "Ta5g",
								"property" =>  array(
									array(
										"text" => "Property5",
										"value" => "Property5"
									)
								 )
							)
						)
					)
					 
				)	
		);
$xml= new GenerateXml();
$xml->xmlArray = $arr;
$xml->run();