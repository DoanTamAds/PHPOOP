<?php 
    $filepath = realpath(dirname(__FILE__));
    include_once ($filepath.'/../lib/database.php');
    include_once ($filepath.'/../helpers/format.php');
?>


<?php



    class product
    {
            private $db;
            private $fm;

           public function __construct()
           {
                $this->db = new Database();
                $this->fm = new Format();
           }
           public function insert_product($data,$files){

                $productName = mysqli_real_escape_string($this->db->link, $data['productName']);
                $category = mysqli_real_escape_string($this->db->link, $data['category']);
                $brand = mysqli_real_escape_string($this->db->link, $data['brand']);
                $product_desc = mysqli_real_escape_string($this->db->link, $data['product_desc']);
                $price = mysqli_real_escape_string($this->db->link, $data['price']);
                $type = mysqli_real_escape_string($this->db->link, $data['type']);
                // ktra hinh anh va lay hinh anh 
                $permited = array('jpg','jpeg','png','gif');
                $file_name = $_FILES['image']['name'];
                $file_size = $_FILES['image']['size'];
                $file_temp = $_FILES['image']['tmp_name'];

                $div = explode('.', $file_name);
                $file_ext = strtolower(end($div));
                $unique_image = substr(md5(time()),0,10).'.'.$file_ext;
                $upload_image = "uploads/".$unique_image;

                if(($productName == "" || $category == "" || $brand == "" || $product_desc == "" || $price == "" || $type == "" || $file_name == "")){
                    $alert = "<span class='error'>Điền vào chố trống</span>";
                    return $alert;
                }else{
                    move_uploaded_file($file_temp,$upload_image);
                    $query = "INSERT INTO tbl_product(productName,brandId,catId,product_desc,price,type,image) VALUES('$productName','$brand','$category',
                    '$product_desc','$price','$type','$unique_image')";
                    $result = $this->db->insert($query);
                    if($result){
                        $alert="<span class='success'>Thêm thành công</span>";
                        return $alert;
                    }else{
                        $alert="<span class='error'>Thêm bị lỗi</span>";
                        return $alert;
                    }
                }
           }

           public function show_product(){
                $query = "
                SELECT tbl_product.*, tbl_category.catName, tbl_brand.brandName
                FROM tbl_product INNER JOIN tbl_category ON tbl_product.catId = tbl_category.catId
                INNER JOIN tbl_brand ON tbl_product.brandId = tbl_brand.brandId
                ORDER BY tbl_product.productId DESC";
            
             
            // $query = "SELECT * FROM tbl_product order by productId desc";
                $result = $this->db->select($query);
                return $result;
           }

            public function update_product($data,$file,$id){

                $productName = mysqli_real_escape_string($this->db->link, $data['productName']);
                $category = mysqli_real_escape_string($this->db->link, $data['category']);
                $brand = mysqli_real_escape_string($this->db->link, $data['brand']);
                $product_desc = mysqli_real_escape_string($this->db->link, $data['product_desc']);
                $price = mysqli_real_escape_string($this->db->link, $data['price']);
                $type = mysqli_real_escape_string($this->db->link, $data['type']);
                // ktra hinh anh va lay hinh anh 
                $permited = array('jpg','jpeg','png','gif');
                $file_name = $_FILES['image']['name'];
                $file_size = $_FILES['image']['size'];
                $file_temp = $_FILES['image']['tmp_name'];

                $div = explode('.', $file_name);
                $file_ext = strtolower(end($div));
                $unique_image = substr(md5(time()),0,10).'.'.$file_ext;
                $upload_image = "uploads/".$unique_image;


                if($productName=="" || $category=="" || $brand=="" || $product_desc=="" || $price=="" || $type=="")
                {
                    $alert = "<span class='error'>Điền vào chố trống</span>";
                    return $alert;
                }else{
                    if(!empty($file_name)){
                        // neu ng dung chon anh
                        if($file_size> 2048000){   
                            $alert = "<span class='success'>Image Size should be less then 2MB!</span>";
                            return $alert;
                        }
                        elseif(in_array($file_ext, $permited) === false)
                        {
                                //echo "<span class='error'>You can upload only:-".implode(',',$permited)."</span>";
                                $alert = "<span class='success'>You can upload only:-".implode(',',$permited)."</span>";
                                return $alert;
                        }
                        move_uploaded_file($file_temp,$upload_image);
                            $query = "UPDATE tbl_product SET 
                                productName = '$productName',
                                catId = '$category',
                                brandId = '$brand',
                                product_desc = '$product_desc',
                                type = '$type',
                                image ='$unique_image',
                                price = '$price'
                                WHERE productId='$id'";
                        }
                        else{
                            // ng dung k chon anh
                            $query = "UPDATE tbl_product SET 
                            productName = '$productName',
                            catId = '$category',
                            brandId = '$brand',
                            product_desc = '$product_desc',
                            type = '$type',
                            price = '$price'
                            WHERE productId='$id'";
                        }
                            $result = $this->db->update($query);
                            if($result){
                                $alert="<span class='success'>Sửa thành công</span>";
                                return $alert;
                            }else{
                                $alert="<span class='error'>Sửa bị lỗi</span>";
                                return $alert;
                            }
                

            }}

            public function del_product($id){
                    $query = "DELETE FROM tbl_product where productId ='$id'";
                    $result = $this->db->delete($query);
                    if($result){
                        $alert="<span class='success'>Xóa thành công</span>";
                        return $alert;
                    }else{
                        $alert="<span class='success'>Xóa không thành công</span>";
                        return $alert;
                    }
            }
            
            
            public function getproductbyId($id){
                    $query = "SELECT * FROM tbl_product where productId ='$id' order by productId desc";
                    $result = $this->db->select($query);
                    return $result;
       }

       //BACKEND
        public function getproduct_feathered(){
                    $query = "SELECT * FROM tbl_product where type ='0' ";
                    $result = $this->db->select($query);
                    return $result;
        }

        public function getproduct_new(){
        $query = "SELECT * FROM tbl_product ORDER BY productId desc limit 4";
        $result = $this->db->select($query);
        return $result;
        }

        public function get_details($id){
            $query = "
                SELECT tbl_product.*, tbl_category.catName, tbl_brand.brandName
                FROM tbl_product INNER JOIN tbl_category ON tbl_product.catId = tbl_category.catId
                INNER JOIN tbl_brand ON tbl_product.brandId = tbl_brand.brandId 
                WHERE tbl_product.productId = '$id' 
                ";
                
                $result = $this->db->select($query);
                return $result;
            }
        }
    
?>