// 現在のファイル名を取得する
function page_name() {
  const url = location.href;
  const array = url.split("/");
  const target = document.getElementById("page_name");
  console.log(array[5]);
  if (array[5] == "item.php") {
    target.textContent = "商品登録";
  } else if (array[5] == "in_stock.php") {
    target.textContent = "商品入荷";
  } else if (array[5] == "shipment.php") {
    target.textContent = "商品出荷";
  } else if (array[5] == "stock.php") {
    target.textContent = "在庫管理";
  } else if (array[5] == "employee.php") {
    target.textContent = "ユーザー管理";
  }
}

// DOM操作用の関数
function ele(ele, class_name, id_name, text, target) {
  let element       = document.createElement(ele);
  element.className = class_name || "";
  element.id = id_name || "";
  element.textContent = text || "";
  return element;
}
