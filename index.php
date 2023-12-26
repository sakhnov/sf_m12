<?php
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

// Функция обьединения ФИО
function getFullnameFromParts( $surname, $name, $patronomyc) {
	return $surname .' '. $name .' '. $patronomyc;
}

// Функция разбиения ФИО
function getPartsFromFullname($fio) {
	$fio_array = explode(" ", $fio);

	return ['surname'=> $fio_array[0], 'name'=> $fio_array[1], 'patronomyc'=> $fio_array[2]];
}

// Функция сокращения ФИО
function getShortName($fio) {
	$fio_array = getPartsFromFullname($fio);
	return $fio_array['name'].' '.mb_substr($fio_array['surname'], 0, 1).'.';
}

// Функция определения пола по ФИО
function getGenderFromName ($fio) {
	$fio_array = getPartsFromFullname($fio);
	$gender = 0;

	// Признаки женского пола
	if (mb_substr($fio_array['patronomyc'], -3, 3) == 'вна' || mb_substr($fio_array['name'], -1, 1) == 'а' || mb_substr($fio_array['surname'], -2, 2) == 'ва') $gender--;

	// Признаки мужского пола
	if (mb_substr($fio_array['patronomyc'], -2, 2) == 'ич' || mb_substr($fio_array['name'], -1, 1) == 'й' || mb_substr($fio_array['name'], -1, 1) == 'н' || mb_substr($fio_array['surname'], -1, 1) == 'в') $gender++;

	return $gender <=> 0;
}


// Функция определения возрастно-полового состава
function getGenderDescription($persons_array) {

	$male_array = [];
	$femme_array = [];
	$unknown_array = [];
	
	$male_array = array_filter($persons_array, function($k) {
       return getGenderFromName($k['fullname']) == 1;
	});
	
	$femme_array = array_filter($persons_array, function($k) {
       return getGenderFromName($k['fullname']) == -1;
	});

	$unknown_array = array_filter($persons_array, function($k) {
       return getGenderFromName($k['fullname']) == 0;
	});

	$male = round(count($male_array)*100/count($persons_array), 1);
	$femme = round(count($femme_array)*100/count($persons_array), 1);
	$unknown = round(count($unknown_array)*100/count($persons_array), 1);

	echo "Гендерный состав аудитории:<br>";
	echo "---------------------------<br>";
	echo "Мужчины - ".$male."%<br>";
	echo "Женщины - ".$femme."%<br>";
	echo "Не удалось определить - ".$unknown."%<br>" ;

}

//Функция подбора идеальной пары
function getPerfectPartner($surname, $name, $patronomyc, $persons_array) {
	$surname = ucfirst(strtolower($surname));
	$name = ucfirst(strtolower($name));
	$patronomyc = ucfirst(strtolower($patronomyc));
	$fio = $surname." ".$name." ".$patronomyc;
	$gender = getGenderFromName($fio); 
	$partner_array = [];

	if ($gender == 1) {
		$partner_array = array_filter($persons_array, function($k) {
	       return getGenderFromName($k['fullname']) == -1;
		});		
	} elseif ($gender == -1) {
		$partner_array = array_filter($persons_array, function($k) {
	       return getGenderFromName($k['fullname']) == 1;
		});	
	} else {
		return "Введные данные ".$fio." не позволяют найти идеальную пару этому человеку.";
	}
	shuffle($partner_array);
	return getShortName($fio)." + ".getShortName($partner_array[0]['fullname'])." =  ♡ <br> Идеально на ".(rand(5000, 10000)/100)."% ♡";

}

//getGenderDescription($example_persons_array);
//echo getPerfectPartner("Петров", "Николай", "Васильевич", $example_persons_array);
//echo getPerfectPartner("Сидорова", "Алиса", "Витальевна", $example_persons_array);
