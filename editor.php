<?
ini_set('memory_limit', '-1');
$d=file_get_contents("in.bin");
$rec=array();$subrec_count=0;$rec_count=0;$pos_count=0;

echo "\n\nBeginning processing file.\n";
while($pos_count<strlen($d)){
$l=implode(unpack("I*",mb_strcut($d,$pos_count+4,4)));
$rp=mb_strcut($d,$pos_count,$l+16);
$rec[$rec_count]=array();$a=0;
$rec[$rec_count][$a]=mb_strcut($rp,0,16);$a++;
$subpos_count=16;
while($subpos_count<strlen($rp)){
$sl=implode(unpack("I*",mb_strcut($rp,$subpos_count+4,4)));
$srp=mb_strcut($rp,$subpos_count,$sl+8);
$rec[$rec_count][$a]=$srp;$a++;
$subrec_count++;$subpos_count=$subpos_count+$sl+8;
}
$rec_count++;$pos_count=$pos_count+$l+16;
}
echo "Found ".($rec_count)." records and ".($subrec_count)." subrecords.\n\n";




function stripascii($dat){
$hex=str_split(implode(unpack("H*",$dat)),2);
$vt="";
foreach($hex as $i3=>$v3){
$v3=hexdec($v3);
if(($v3<127)&&($v3>31)){
$vt.=chr($v3);
}
else{
//$vt.="".$v3."";
}
}
return $vt."\n";
}




foreach($rec as $i=>$v){
foreach($v as $i2=>$v2){
$t=implode(unpack("A*",mb_strcut($v2,0,4)));
$tl=implode(unpack("I*",mb_strcut($v2,4,4)));
if($i2==0){
$hexa=unpack("H*",mb_strcut($v2,16,strlen($v2)-16));
}else{
$hexa=unpack("H*",mb_strcut($v2,8,strlen($v2)-8));
}
$hex=str_split($hexa[1],2);
$vt="";
foreach($hex as $i3=>$v3){
$v3=hexdec($v3);
if(($v3<127)&&($v3>31)){
$vt.=chr($v3);
}
else{
$vt.=" ".$v3." ";
}
}
//echo $i."(".$i2.") ".$t." ".$vt."\n";
}
}

echo "Processing complete. Beginning write.\n\n";

$wo="";
foreach($rec as $i=>$v){
foreach($v as $i2=>$v2){
if(($i<4)||($i>5)){
$wo.=$v2;
}
}
}
file_put_contents("out.bin",$wo);

echo "Writing complete.\n\n";


?>