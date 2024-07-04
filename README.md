<h2>BỘ GIÁO DỤC VÀ ĐÀO TẠO</h2>
<h1>PHÂN HIỆU TRƯỜNG ĐẠI HỌC BÌNH DƯƠNG - CÀ MAU</h1>
#ĐỒ ÁN TỐT NGHIỆP
<h2> Đề tài: Xây dựng hệ thống bán hàng thương mại điện tử đa nền tảng. </h3>
##GVTD TH.S: TRẦN HỮU DUÂT
##SVTH: TRẦN THÀNH PHỐ 
##MSSV: 200501022
<h3> Đề tài: Xây dựng hệ thống bán hàng thương mại điện tử đa nền tảng. </h3>

<h3>Ấn để tải Source và cơ sở dữ liệu: <a href="https://codeload.github.com/cityiscoding/ecommerce_/zip/refs/heads/main" target="_blank">[http://localhost/phpmyadmin](https://codeload.github.com/cityiscoding/ecommerce_/zip/refs/heads/main)</a>  </h2>
<h1>Hướng dẫn cấu hình chạy website trên localhost: </h1> 
<h2>Bước 1: </h2> 
<p>Tải phần mềm Xampp tại <a href="https://www.apachefriends.org/download.html" target="_blank">ĐÂY</a>.</p>
<h2>Bước 2: </h2> Tiến hành cài đặt ứng dụng Xampp đã tải về. Sau đó khởi động ứng dụng. Kích hoạt Apache và MYSQL.
(https://github.com/cityiscoding/ecommerce_/assets/129607064/7f6d0ce9-9a13-447e-b910-10e732dbbef2)
<h2>Bước 3: </h2> Truy cập vào thư mục chứa Xampp. Mặc định là C://xampp/htdocs/
(https://github.com/cityiscoding/ecommerce_/assets/129607064/0fe9d859-734e-4d94-ba6e-54785fba6a85)
<h2>Bước 4: </h2> Tại đây hãy tạo một thư mục mới tên bất kỳ sao đó sao chép toàn bộ mã nguồn của đề tài vào bên trong tên thư mục đã tạo : C://xampp/htdocs/

<h2>Bước 5: </h2> Truy cập trình duyệt và truy cập: <a href="http://localhost/phpmyadmin" target="_blank">http://localhost/phpmyadmin</a> 
<h2>Bước 6: </h2> Tiến hành tạo một database mới tên là "pallmall_dtb" và ấn "Create".
![image](https://github.com/cityiscoding/ecommerce_/assets/129607064/5be6829b-06ab-4992-82f3-078a053fe905)

<h2>Bước 7: </h2> Sau khi tạo Database thành công. Tiến hành "Nhập" file dữ liệu MYSQL có tên là "dtb.sql" trong mã nguồn sau đó chọn IMPORT để tải dữ liệu vào tên Database đa tạo trước đó.
![image](https://github.com/cityiscoding/ecommerce_/assets/129607064/9865d57e-65a6-4519-a475-8ee5e97ef536)
<h2>Bước 8: </h2> Truy cập vào bảng "wp_options" của cơ sở dữ liệu để điều chỉnh thông tin về đường dẫn và Email quản trị. Tiến hành cập nhật theo đường dẫn tệp đã tạo ở bước 4: Mặc định sẽ là: http://localhost/phpmyadmin/ten_thu_muc_luu_source_codeo . Chỉnh sửa 2 trường là "siteurl" và "home" tương tự nhau.
![image](https://github.com/cityiscoding/ecommerce_/assets/129607064/6b97c0d5-7951-4812-b5df-bb44a65b811b)

## Sau khi hoàn thành các bước trên. Truy cập vào trình duyệt và gõ : <a href="http://localhost/phpmyadmin" target="_blank">http://localhost/phpmyadmin/ten_thu_muc_luu_source_code</a> 
### Lưu ý: cần thay đổi "ten_thu_muc_luu_source_code" giống như tên thư mục đã tạo ở bước 4.
## Cách truy cập trang quản trị viên:  Truy cập vào trình duyệt và gõ : <a href="http://localhost/phpmyadmin" target="_blank">http://localhost/phpmyadmin/ten_thu_muc_luu_source_code/wp-admin</a>
### Tên đăng nhập là "admin" và mật khẩu là "admin". Có thể chỉnh sửa thông tin chi tiết ở bảng "wp_user" tham khảo :https://vn.godaddy.com/help/thay-doi-mat-khau-wordpress-cua-toi-trong-co-so-du-lieu-26920

# Kết thúc hướng dẫn. Em xin chân thành cảm ơn!


