<?php

foreach ($navigation as $row) {
	$oEnd = end($row['way']);
	
	foreach ($oParentChild[$oEnd] as $OChild) {
		
		$way = array();
		$way[] = $OChild;
		$way = array_merge($row['way'], $way);
		$way = array_unique($way);
		
		$driveway = array();
		$driveway[$oEnd]['o1'] = $oEnd;
		$driveway[$oEnd]['o2'] = $OChild;
			
		foreach ($r as $R) {
			if ($R['o1']==$oEnd && $R['o2']==$OChild) {
			
				if ($R['rt1']>0) {$driveway[$oEnd]['rt'] = $rt[$R['rt1']]['name'];} 
				else {$driveway[$oEnd]['rt'] = "";}
			}
		}
		$driveway = array_merge($row['driveway'], $driveway);

		
		if (end($way) == $OChild) {
			if (count($way)>$i) {

				$navigation[$ii]['id'] = $ii;
				$navigation[$ii]['o1'] = $row['o1'];
				$navigation[$ii]['o2'] = $OChild;
				$navigation[$ii]['way'] = $way;
				$navigation[$ii]['driveway'] = $driveway;
				$navigation[$ii]['count'] = count($way);
				
				$ii++;
				
			}
		}
	}
}
?>