<?php
$fild_state = array("выгружен на область","отправлен в МНС", "действующий", "прекращен", "отказано");
$request_state = array("предоставление", "прекращение", "продление", "разблокировка", "изменить реквизиты");
$certState = array("действующий", "прекращен");
$certName = array("ГосСУОК", "МНС", "КлиентБанк", "КлиентТК");
$localState = array("сформирован","действующий", "прекращен");
$sertBase = array("п.1 ПК Расчет налогов", "п.2 АИС РН республика", "п.3 ПК Таможенный союз", "п.4 АИС КОНТРОЛЬНАЯ РАБОТА",
    "п.5 АИС ЭСЧФ", "п.6 АИС ГРП (за исключением АИС Взаимодействие)",
    "п.7 АИС ГРП (использозуется АИС Взаимодействие)(ГОССУОК)",
    "п.8 АИС УДФЛ", "п.9 АСЭД Директум", "п.10 АСЭД Директум, утверждающая подпись(ГОССУОК)",
    "п.11 СККС", "п.12 СКТА", "п.13 СККО", "п.14 Сведения для Министерства труда и социальной защиты",
    "п.15 Сведения для ФСЗН", "п.16 Статистика Электронный респондент", "п.17 Белгосстрах",
    "п.18 Госзакупки товаров(работ, услуг)(ГОССУОК)", "п.19 АС БДБ(ГОССУОК)", "п.20 ГИР ФСЗН услуга 3.25(ГОССУОК)",
    "п.21 ГИС Регистр населения (ГОССУОК)", "п.22 Центр регистрации АВЕСТ");

$newLoginNotice ="%IP - последние цифры IP-адреса; %IMNS - код ИМНС; %F - фалимилия; %N - Имя; %P - отчество; %I - инициацы; %FIO - первые буквы ФИО; %C(_) - количество одинаковых логинов (в скобочках указывается символ перед количеством)";
$exempleLogin = "%IMNS_%F_%I_%IP -> 301_ivanov_ii_017";

$avtoNotice = "<b>Инспекция:</b> <b>%NNU</b> - Код инспекции; <b>%NNA</b> - Полное наименование инспекции; <b>%NSN</b> - Сокращенное наименование инспекции; <b>%NU</b> - УНП инспекции; <b>%NP</b> - Почтовый индекс инспекции; <b>%NA</b> - Адрес инспекции; <b>%NM</b> – Электронная почта инспекции. <br><br><b>Пользовательские данные:</b> <b>%UF</b> - ФИО; <b>%UFS</b> - Фамилия; <b>%UFN</b> - Имя; <b>%UFP</b> - Отчество; <b>%RL</b> - Логин; <b>%JN</b> - Должность; <b>%UN</b> - Подразделение; <b>%UI</b> – IP-адрес; <b>%UT</b> - Телефон; <b>%RN</b> – Примечание <b>%RU</b> - Номер заявки. <br><br><b>Банковские данные:</b> <b>%NBI</b> - BIK; <b>%NSC</b> - Счет; <b>%NB</b> - Название банка; <b>%NBA</b> - Адрес банка.";
$locaAvtoNotice = "<b>Инспекция:</b> <b>%NNU</b> - Код инспекции; <b>%NNA</b> - Полное наименование инспекции; <b>%NSN</b> - Сокращенное наименование инспекции; <b>%NU</b> - УНП инспекции; <b>%NP</b> - Почтовый индекс инспекции; <b>%NA</b> - Адрес инспекции; <b>%NM</b> – Электронная почта инспекции. <br><br><b>Пользовательские данные:</b> <b>%UF</b> - ФИО; <b>%UFS</b> - Фамилия; <b>%UFN</b> - Имя; <b>%UFP</b> - Отчество; <b>%RL</b> - Логин; <b>%JN</b> - Должность; <b>%UN</b> - Подразделение; <b>%UI</b> – IP-адрес; <b>%UT</b> - Телефон; <b>%RN</b> – Примечание.; <b>%RU</b> - Номер заявки <br><br><b>Банковские данные:</b> <b>%NBI</b> - BIK; <b>%NSC</b> - Счет; <b>%NB</b> - Название банка; <b>%NBA</b> - Адрес банка.";
?>