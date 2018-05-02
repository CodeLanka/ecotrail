<?php
namespace lk\gdgsrilanka\io18;
header("Access-Control-Allow-Origin: *");

include_once("User.php");


$keyContent = file_get_contents('php://input');

$data = json_decode($keyContent);
// var_dump($data);
if(isset($data->{'emailTree'}) && isset($data->{'emailWater'}) && isset($data->{'type'}))
{
	//email is present
	if(isset($data->{"keyWater"}) && isset($data->{"keyTree"}))
	{
		if(count($data->{"keyWater"}) == 4 && count($data->{"keyTree"}) == 4)
		{
			//everything is fine here.
			$treeUser = new User($data->{"emailTree"});
			$validTreeKeys = $treeUser->getKeysForSteps();

			$waterUser = new User($data->{"emailWater"});
			$validWaterKeys = $waterUser->getKeysForSteps();

			//check if water type is water and tree is tree.
			if($waterUser->getUserDockerType() == 2 && $treeUser->getUserDockerType() == 1)
			{

				if(getMatchCountForKeys($validTreeKeys, $data->{"keyTree"}) == 4 && getMatchCountForKeys($validWaterKeys, $data->{"keyWater"}) == 4)
				{
					//keys match. send the last hash for given user type.
					if(strcasecmp($data->{"type"}, "water") == 0)
					{
						//give the water key
						$out = array();
						$out['state'] = TRUE;
						$out['message'] = $waterUser->getFinalKey();
						header("Content-Type: application/json");
						echo json_encode($out);

					} 
					elseif(strcasecmp($data->{"type"}, "tree") == 0)
					{
						//give the tree key
						$out = array();
						$out['state'] = TRUE;
						$out['message'] = $treeUser->getFinalKey();
						header("Content-Type: application/json");
						echo json_encode($out);
					}
				}
				else
				{
					//keys wrong
					$out = array();
					$out['state'] = FALSE;
					$out['message'] = "wrong keys used. Make sure you use correct keys";
					header("Content-Type: application/json");
					echo json_encode($out);
				}
			} 
			else 
			{
				//types wrong
				$out = array();
				$out['state'] = FALSE;
				$out['message'] = "A tree needs to be a tree and water needs to be water.";
				header("Content-Type: application/json");
				echo json_encode($out);
			}

		}
	}
	else 
	{
		//water and tree keys not set
		$out = array();
		$out['state'] = FALSE;
		$out['message'] = "Hello buddy. I see you have lost the keys again...";
		header("Content-Type: application/json");
		echo json_encode($out);
	}
}
else 
{
	$out = array();
	$out['state'] = FALSE;
	$out['message'] = "App failure. Who is who?";
	header("Content-Type: application/json");
	echo json_encode($out);
}





function getMatchCountForKeys($validKeys, $givenKeys)
{
	// var_dump($validKeys);
	// var_dump($givenKeys);
	$matches = 0;
	for ($i=0; $i < count($validKeys); $i++) { 
		for ($j=0; $j < count($givenKeys); $j++) { 
			if(strcasecmp($validKeys[$i+1], $givenKeys[$j]) == 0)
			{
				//key match
				$matches += 1;
				break;
			}
		}
	}

	return $matches;
}
