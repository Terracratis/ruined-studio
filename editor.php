<?php
	include('../ruinedstudio.php');
	$instudio = true;
	$DB = _database_connect( $config_mysql['host'], $config_mysql['database'], $config_mysql['username'], $config_mysql['password'] );
	$SQL = $DB->prepare("SELECT * FROM studio_settings WHERE settings = ? LIMIT 1");
	$SQL->execute(array(1));
	$settings = $SQL->fetch();
	ob_start("ober");
	$acc = _loged_in_studio();
	list($page, $type, $row) = explode("/", _get('id'));
	$value = _get('value');
	if ($acc['user_id'])
		{
			if ($page == 'product-add')
				{
					if ($type == "price")
						{
							$value = str_replace(',','.',$value);
							$SQL = $DB -> prepare( "UPDATE studio_products SET price=? WHERE product_id=? LIMIT 1" );
							$SQL -> execute (array($value, $row));
							echo _money($value);
						}
					else if ($type == "price-sell")
						{
							$value = str_replace(',','.',$value);
							$SQL = $DB -> prepare( "UPDATE studio_products SET price_to_make=? WHERE product_id=? LIMIT 1" );
							$SQL -> execute (array($value, $row));
							echo _money($value);
						}
					else if ($type == "draft-save")
						{
							$SQL = $DB -> prepare( "UPDATE studio_products SET is_draft=?, date_added = ? WHERE product_id=? LIMIT 1" );
							$SQL -> execute (array("0", $value, date("Y-m-d")));
							echo "OK";
						}
					else if ($type == "draft-menu")
						{
							$SQL = $DB -> prepare( "UPDATE studio_products SET is_draft=?, date_added = ? WHERE product_id=? LIMIT 1" );
							$SQL -> execute (array("0",  date("Y-m-d"), $value));
							$SQL = $DB->prepare("INSERT INTO dish_menu (product_id, date_added) VALUES (?, ?)");
							$SQL->execute(array($value, date("Y-m-d")));
							echo "OK";
						}
					else if ($type == "delete")
						{
							$SQL = $DB -> prepare( "DELETE FROM studio_products WHERE product_id=? LIMIT 1" );
							$SQL -> execute (array($value));
							echo "OK";
						}
					else
						{
							echo "Įvyko klaida.";
						}
				}
			else if ($page == 'feeds')
				{
					if ($type == "delete")
						{
							$SQL = $DB -> prepare( "DELETE FROM studio_feedbacks WHERE feedback_id=? LIMIT 1" );
							$SQL -> execute (array($value));
							echo "OK";
						}
					else if ($type == "add")
						{
							$SQL = $DB -> prepare( "UPDATE studio_feedbacks SET active=? WHERE feedback_id=? LIMIT 1" );
							$SQL -> execute (array(1, $value));
							echo "OK";
						}
					else
						{
							echo "Įvyko klaida.";
						}
				}
			else if ($page == 'menu-item')
				{
					if ($type == "change-category")
						{
							$SQL = $DB -> prepare( "UPDATE dish_menu SET category_id=? WHERE menu_id=? LIMIT 1" );
							$SQL -> execute (array($value, $row));
							$SQL = $DB->prepare("SELECT * FROM dish_categories WHERE is_draft = 0 AND category_id = ? LIMIT 1");
							$SQL->execute(array($value));
							$category = $SQL->fetch();
							echo _MultiKey($category['name_key'], $set['lang']);
						}
					else if ($type == "category-save")
						{
							$SQL = $DB -> prepare( "UPDATE dish_categories SET is_draft=? WHERE category_id=? LIMIT 1" );
							$SQL -> execute (array("0", $value));
							echo "OK";
						}
					else if ($type == "add-slide")
						{
							$SQL = $DB -> prepare( "UPDATE dish_menu SET in_slides=? WHERE menu_id=? LIMIT 1" );
							$SQL -> execute (array("1", $value));
							echo "OK";
						}
					else if ($type == "delete-slide")
						{
							$SQL = $DB -> prepare( "UPDATE dish_menu SET in_slides=? WHERE menu_id=? LIMIT 1" );
							$SQL -> execute (array("0", $value));
							echo "OK";
						}
					else if ($type == "category-delete")
						{
							$SQL = $DB -> prepare( "DELETE FROM dish_categories WHERE category_id=? LIMIT 1" );
							$SQL -> execute (array($value));
							echo "OK";
						}
					else if ($type == "delete")
						{
							$SQL = $DB -> prepare( "DELETE FROM dish_menu WHERE menu_id=? LIMIT 1" );
							$SQL -> execute (array($value));
							echo "OK";
						}
					else if ($type == "category-up")
						{
							// sustatom kategorijas i eilę
							$SQL = $DB->prepare("SELECT position, category_id FROM dish_categories WHERE is_draft = 0 ORDER BY position ASC");
							$SQL->execute(array($value));
							$category_pos = $SQL->fetchAll();
							$i=0;
							foreach ($category_pos as $got)
								{
									$i++;
									$SQL = $DB -> prepare( "UPDATE dish_categories SET position=? WHERE category_id=? LIMIT 1" );
									$SQL -> execute (array($i, $got['category_id']));
								}
							// kategorijos pos
							$SQL = $DB->prepare("SELECT position FROM dish_categories WHERE is_draft = 0 AND category_id = ? LIMIT 1");
							$SQL->execute(array($value));
							$category = $SQL->fetch();
							// vienos aukštesnės kategorijos pos
							$SQL = $DB->prepare("SELECT position, category_id FROM dish_categories WHERE is_draft = 0 AND position = ? LIMIT 1");
							$SQL->execute(array(($category['position']-1)));
							$position = $SQL->fetch();
							// apkeiciam vietom
							if ($position['category_id'])
								{
									$SQL = $DB -> prepare( "UPDATE dish_categories SET position=? WHERE category_id=? LIMIT 1" );
									$SQL -> execute (array($position['position'], $value));
									$SQL = $DB -> prepare( "UPDATE dish_categories SET position=? WHERE category_id=? LIMIT 1" );
									$SQL -> execute (array($category['position'], $position['category_id']));
								}
							//print_r($position);
							
							echo "OK";
						}
					else if ($type == "category-down")
						{
							// sustatom kategorijas i eilę
							$SQL = $DB->prepare("SELECT position, category_id FROM dish_categories WHERE is_draft = 0 ORDER BY position ASC");
							$SQL->execute(array($value));
							$category_pos = $SQL->fetchAll();
							$i=0;
							foreach ($category_pos as $got)
								{
									$i++;
									$SQL = $DB -> prepare( "UPDATE dish_categories SET position=? WHERE category_id=? LIMIT 1" );
									$SQL -> execute (array($i, $got['category_id']));
								}
							// kategorijos pos
							$SQL = $DB->prepare("SELECT position FROM dish_categories WHERE is_draft = 0 AND category_id = ? LIMIT 1");
							$SQL->execute(array($value));
							$category = $SQL->fetch();
							// vienos zemesne kategorijos pos
							$SQL = $DB->prepare("SELECT position, category_id FROM dish_categories WHERE is_draft = 0 AND position = ? LIMIT 1");
							$SQL->execute(array(($category['position']+1)));
							$position = $SQL->fetch();
							// apkeiciam vietom
							if ($position['category_id'])
								{
									$SQL = $DB -> prepare( "UPDATE dish_categories SET position=? WHERE category_id=? LIMIT 1" );
									$SQL -> execute (array($position['position'], $value));
									$SQL = $DB -> prepare( "UPDATE dish_categories SET position=? WHERE category_id=? LIMIT 1" );
									$SQL -> execute (array($category['position'], $position['category_id']));
								}
							//print_r($position);
							
							echo "OK";
						}
					else if ($type == "product-up")
						{
							// sustatom kategorijas i eilę
							$SQL = $DB->prepare("SELECT position, product_id FROM studio_products WHERE is_draft = 0 ORDER BY position ASC");
							$SQL->execute(array($value));
							$category_pos = $SQL->fetchAll();
							$i=0;
							foreach ($category_pos as $got)
								{
									$i++;
									$SQL = $DB -> prepare( "UPDATE studio_products SET position=? WHERE product_id=? LIMIT 1" );
									$SQL -> execute (array($i, $got['product_id']));
								}
							// kategorijos pos
							$SQL = $DB->prepare("SELECT position FROM studio_products WHERE is_draft = 0 AND product_id = ? LIMIT 1");
							$SQL->execute(array($value));
							$category = $SQL->fetch();
							// vienos aukštesnės kategorijos pos
							$SQL = $DB->prepare("SELECT position,product_id FROM studio_products WHERE is_draft = 0 AND position = ? LIMIT 1");
							$SQL->execute(array(($category['position']-1)));
							$position = $SQL->fetch();
							// apkeiciam vietom
							if ($position['product_id'])
								{
									$SQL = $DB -> prepare( "UPDATE studio_products SET position=? WHERE product_id=? LIMIT 1" );
									$SQL -> execute (array($position['position'], $value));
									$SQL = $DB -> prepare( "UPDATE studio_products SET position=? WHERE product_id=? LIMIT 1" );
									$SQL -> execute (array($category['position'], $position['product_id']));
								}
							//print_r($position);
							
							echo "OK";
						}
					else if ($type == "product-down")
						{
							// sustatom kategorijas i eilę
							$SQL = $DB->prepare("SELECT position, product_id FROM studio_products WHERE is_draft = 0 ORDER BY position ASC");
							$SQL->execute(array($value));
							$category_pos = $SQL->fetchAll();
							$i=0;
							foreach ($category_pos as $got)
								{
									$i++;
									$SQL = $DB -> prepare( "UPDATE studio_products SET position=? WHERE product_id=? LIMIT 1" );
									$SQL -> execute (array($i, $got['product_id']));
								}
							// kategorijos pos
							$SQL = $DB->prepare("SELECT position FROM studio_products WHERE is_draft = 0 AND product_id = ? LIMIT 1");
							$SQL->execute(array($value));
							$category = $SQL->fetch();
							// vienos aukštesnės kategorijos pos
							$SQL = $DB->prepare("SELECT position,product_id FROM studio_products WHERE is_draft = 0 AND position = ? LIMIT 1");
							$SQL->execute(array(($category['position']+1)));
							$position = $SQL->fetch();
							// apkeiciam vietom
							if ($position['product_id'])
								{
									$SQL = $DB -> prepare( "UPDATE studio_products SET position=? WHERE product_id=? LIMIT 1" );
									$SQL -> execute (array($position['position'], $value));
									$SQL = $DB -> prepare( "UPDATE studio_products SET position=? WHERE product_id=? LIMIT 1" );
									$SQL -> execute (array($category['position'], $position['product_id']));
								}
							//print_r($position);
							
							echo "OK";
						}
					else if ($type == "add")
						{
							
							$SQL = $DB->prepare("SELECT * FROM studio_products WHERE is_draft = 0 AND product_id = ? LIMIT 1");
							$SQL->execute(array($value));
							$product = $SQL->fetch();
							if (!$product)
								{
									echo "Pasirinkite tinkamą produktą!";
								}
							else
								{
									$SQL = $DB->prepare("SELECT * FROM dish_menu WHERE product_id = ? LIMIT 1");
									$SQL->execute(array($value));
									$product = $SQL->fetch();
									if (!$product)
										{
											$SQL = $DB -> prepare( "INSERT INTO dish_menu (product_id, date_added) VALUES (?, ?)" );
											$SQL -> execute (array($value, date("Y-m-d")));
											echo "OK";
										}
									else
										{
											echo "Produktas jau yra valgiaraštyje!";
										}
								}
						}
					else
						{
							echo "Įvyko klaida.";
						}
				}
			else if ($page == 'orders-view')
				{
					if (perm("orders",$acc['rights']))
					{
					if ($type == "Accept")
						{
							$SQL = $DB -> prepare( "UPDATE studio_orders SET state=?, date = ? WHERE order_id=? LIMIT 1" );
							$SQL -> execute (array("accepted", $set['time'], $value));
							echo "OK";
						}
					else if ($type == "Reject")
						{
							$SQL = $DB -> prepare( "UPDATE studio_orders SET state=?, date = ? WHERE order_id=? LIMIT 1" );
							$SQL -> execute (array("rejected", $set['time'], $value));
							$SQL = $DB -> prepare( "UPDATE studio_orders_list SET state=?, date = ? WHERE order_id=? LIMIT 1" );
							$SQL -> execute (array("rejected", $set['time'], $value));
							echo "OK";
						}
					else if ($type == "Sent")
						{
							$SQL = $DB -> prepare( "UPDATE studio_orders SET state=?, date = ? WHERE order_id=? LIMIT 1" );
							$SQL -> execute (array("sent", $set['time'], $value));
							echo "OK";
						}
					else if ($type == "Delivered")
						{
							// Gaunama užsakymo info
							$SQL = $DB->prepare("SELECT * FROM studio_orders WHERE order_id = ? LIMIT 1");
							$SQL->execute(array($value));
							$order = $SQL->fetch();
							// Keičiamas užsakymo ir produktu statusas
							$SQL = $DB -> prepare( "UPDATE studio_orders SET state=?, date = ? WHERE order_id=? LIMIT 1" );
							$SQL -> execute (array("delivered", $set['time'], $value));
							$SQL = $DB -> prepare( "UPDATE studio_orders_list SET state=?, date = ? WHERE order_id=? LIMIT 1" );
							$SQL -> execute (array("delivered", $set['time'], $value));
							// Atnaujinami kliento užsakymai
							$SQL = $DB -> prepare( "UPDATE studio_commerce_clients SET bought=bought+1 WHERE commerce_client=? LIMIT 1" );
							$SQL -> execute (array($order['client_id']));
							// Gaunam užsakytus produktus ir pažymime kad jie buvo nupirkti.
							$SQL = $DB->prepare("SELECT * FROM studio_orders_list WHERE order_id = ?");
							$SQL->execute(array($value));
							$items = $SQL->fetchAll();
							if ($items)
								{
									foreach($items as $item)
										{
											$SQL = $DB -> prepare( "UPDATE studio_products SET sold=sold+? WHERE product_id=? LIMIT 1" );
											$SQL -> execute (array($item['count'], $item['product_id']));
										}
								}
							echo "OK";
						}
					else if ($type == "NotPaid")
						{
							$SQL = $DB->prepare("SELECT * FROM studio_orders WHERE order_id = ? LIMIT 1");
							$SQL->execute(array($value));
							$order = $SQL->fetch();
							$SQL = $DB -> prepare( "UPDATE studio_orders SET state=?, date = ? WHERE order_id=? LIMIT 1" );
							$SQL -> execute (array("not_paid", $set['time'], $value));
							$SQL = $DB -> prepare( "UPDATE studio_orders_list SET state=?, date=? WHERE order_id=? LIMIT 1" );
							$SQL -> execute (array("not_paid", $set['time'], $value));
							
							$SQL = $DB -> prepare( "UPDATE studio_commerce_clients SET indepted=indepted+? WHERE commerce_client=? LIMIT 1" );
							$SQL -> execute (array($order['price'], $order['client_id']));
							echo "OK";
						}
					else if ($type == "Delete")
						{
							$SQL = $DB -> prepare( "UPDATE studio_orders SET state=?, date = ? WHERE order_id=? LIMIT 1" );
							$SQL -> execute (array("delete", $set['time'], $value));
							$SQL = $DB -> prepare( "UPDATE studio_orders_list SET state=?, date = ? WHERE order_id=? LIMIT 1" );
							$SQL -> execute (array("delete", $set['time'], $value));
							echo "OK";
						}
					else if ($type == "alarm")
						{
							$SQL = $DB->prepare("SELECT COUNT(order_id) FROM studio_orders WHERE status=?");
							$SQL->execute(array("new"));
							$count = $SQL->fetch();
							echo $count[0];
							$SQL = $DB -> prepare( "UPDATE studio_orders SET status=? WHERE status=?" );
							$SQL -> execute (array("check", "new"));
						}
					else if ($type == "check")
						{
							$SQL = $DB->prepare("SELECT COUNT(order_id) FROM studio_orders WHERE state=?");
							$SQL->execute(array("new"));
							$count = $SQL->fetch();
							echo $count[0];
						}
					else
						{
							echo "Įvyko klaida.";
						}
					
				}
						else
						{
							echo "Neturite teisių.";
						}
				}
			else if ($page == 'orders-sview')
				{
					if (perm("orders",$acc['rights']))
					{
					if ($type == "Accept")
						{
							$SQL = $DB -> prepare( "UPDATE studio_orders_sub SET state=?, date = ? WHERE order_id=? LIMIT 1" );
							$SQL -> execute (array("accepted", $set['time'], $value));
							echo "OK";
						}
					else if ($type == "Reject")
						{
							$SQL = $DB -> prepare( "DELETE FROM studio_orders_sub WHERE order_id=? LIMIT 1" );
							$SQL -> execute (array($value));
							echo "OK";
						}
					else if ($type == "alarm")
						{
							$SQL = $DB->prepare("SELECT COUNT(order_id) FROM studio_orders_sub WHERE status=?");
							$SQL->execute(array("new"));
							$count = $SQL->fetch();
							echo $count[0];
							$SQL = $DB -> prepare( "UPDATE studio_orders_sub SET status=? WHERE status=?" );
							$SQL -> execute (array("check", "new"));
						}
					else if ($type == "check")
						{
							$SQL = $DB->prepare("SELECT COUNT(order_id) FROM studio_orders_sub WHERE state=?");
							$SQL->execute(array("new"));
							$count = $SQL->fetch();
							echo $count[0];
						}
					else
						{
							echo "Įvyko klaida.";
						}
					
				}
						else
						{
							echo "Neturite teisių.";
						}
				}
			else if ($page == 'language-key')
				{
					if (in_array($type, $config_lang['available']))
						{
							$SQL = $DB->prepare("SELECT * FROM studio_language WHERE language = ? AND lang_key = ? AND type = ? LIMIT 1");
							$SQL->execute(array($type, $row, 'data'));
							$key = $SQL->fetch();
							if (!$key)
								{
									$SQL = $DB->prepare("INSERT INTO studio_language (lang_key, type, language, translation) VALUES (?, ?, ?, ?)");
									$SQL->execute(array($row,"data",$type,$value));
									echo $value;
								}
							else
								{
									$SQL = $DB -> prepare( "UPDATE studio_language SET translation = ? WHERE lang_key = ? AND language = ? LIMIT 1" );
									$SQL -> execute (array($value, $key['lang_key'], $type));
									echo $value;
								}
						}
					else
						{
							echo "Klaida.";
						}
				}
			else if ($page == 'language-web')
				{
					if (in_array($type, $config_lang['available']))
						{
							$SQL = $DB->prepare("SELECT * FROM studio_language WHERE language = ? AND lang_key = ? AND type = ? LIMIT 1");
							$SQL->execute(array($type, $row, 'web'));
							$key = $SQL->fetch();
							if (!$key)
								{
									$SQL = $DB->prepare("INSERT INTO studio_language (lang_key, type, language, translation) VALUES (?, ?, ?, ?)");
									$SQL->execute(array($row,"web",$type,$value));
									echo $value;
								}
							else
								{
									$SQL = $DB -> prepare( "UPDATE studio_language SET translation = ? WHERE lang_key = ? AND language = ? AND type = ? LIMIT 1" );
									$SQL -> execute (array($value, $key['lang_key'], $type, 'web'));
									echo $value;
								}
						}
					else
						{
							echo "Klaida.";
						}
				}
			else if ($page == "logout")
				{
					unset($_SESSION['RS_username'],$_SESSION['RS_password'],$_SESSION['RS_logined']);
					echo "OK";
				}
			else if ($page == 'settings')
				{
					if ($type == "address")
						{
							$SQL = $DB -> prepare( "UPDATE studio_settings SET address = ? WHERE settings = ? LIMIT 1" );
							$SQL -> execute (array($value, '1'));
							echo $value;
						}
					else if ($type == "phone")
						{
							$SQL = $DB -> prepare( "UPDATE studio_settings SET phone = ? WHERE settings = ? LIMIT 1" );
							$SQL -> execute (array($value, '1'));
							echo $value;
						}
					else if ($type == "email")
						{
							$SQL = $DB -> prepare( "UPDATE studio_settings SET email = ? WHERE settings = ? LIMIT 1" );
							$SQL -> execute (array($value, '1'));
							echo $value;
						}
					else if ($type == "ship")
						{
							$SQL = $DB -> prepare( "UPDATE studio_settings SET shipping_price = ? WHERE settings = ? LIMIT 1" );
							$SQL -> execute (array($value, '1'));
							echo _money($value);
						}
					else if ($type == "ship_free")
						{
							$SQL = $DB -> prepare( "UPDATE studio_settings SET shipping_free_if_price = ? WHERE settings = ? LIMIT 1" );
							$SQL -> execute (array($value, '1'));
							echo _money($value);
						}
					else if ($type == "city")
						{
							$SQL = $DB -> prepare( "UPDATE studio_settings SET shipping_free_if_city = ? WHERE settings = ? LIMIT 1" );
							$SQL -> execute (array($value, '1'));
							echo $value;
						}
					else if ($type == "tax")
						{
							$SQL = $DB -> prepare( "UPDATE studio_settings SET tax = ? WHERE settings = ? LIMIT 1" );
							$SQL -> execute (array($value, '1'));
							echo _money($value);
						}
					else { echo "Wut"; }
				}
			else if ($page == 'me')
				{
					if ($type == "name")
						{
							if (strlen($value)<5) { echo "Vardas per trumpas."; }
							else {
							$SQL = $DB -> prepare( "UPDATE studio_users SET name = ? WHERE user_id = ? LIMIT 1" );
							$SQL -> execute (array($value, $acc['user_id']));
							echo $value;
							}
						}
					else if ($type == "pass")
						{
							if (strlen($value)<8) { echo "Slaptažodis per trumpas."; }
							else {
							$pwd = str_repeat("*",strlen($value));
							$SQL = $DB -> prepare( "UPDATE studio_users SET password = ? WHERE user_id = ? LIMIT 1" );
							$SQL -> execute (array(sha1($value), $acc['user_id']));
							echo $pwd;
							unset($_SESSION['RS_username'],$_SESSION['RS_password'],$_SESSION['RS_logined']);
							}
						}
					else { echo "Wut"; }
				}
			else
				{
					echo "Klaida";
				}
		}
	else
		{
			if ($page == "login")
				{
					$login = $type;
					$password = $row;
					$password_sh = sha1($password);
					$SQL = $DB -> prepare( "SELECT * FROM studio_users WHERE username=? AND password=? LIMIT 1");
					$SQL -> execute (array($login, $password_sh));
					$user = $SQL -> fetch();
					if ( empty($user['user_id']))
						{
							echo "Blogi prisijungimo duomenys.";
						}
					else
						{	
							$_SESSION['RS_username'] = $login;
							$_SESSION['RS_password'] = $password_sh;
							$_SESSION['RS_logined'] = $user['user_id'];

							echo "OK";
						}
				}
			else
				{
					echo "Būtina prisijungti!<script>window.location='".$_SERVER['HTTP_REFERER']."';</script>";
				}
		}
	ob_end_flush();
	exit;
?>