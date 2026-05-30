bạn biết trang https://www.foxnews.com chứ. giờ tạo giúp tôi một trang như thế nhé nhưng sẽ lược bớt nội dung. nội dung sẽ gồm:

yêu cầu trước: - trang giờ mỹ nhé. editor dùng classic editor
- bài mới nhất lên trước, mỗi post có thumbnail riêng
- trang này cần SEO tốt nhé
- cần responsive cho cả mobile tablet và pc nhé
- cần làm gì phía admin wp hoặc hỏi thêm gì thì nói tôi nhé
- trang này mục đích để chèn quảng cáo để ăn rpm từ các nguồn quảng cáo thông qua như là banner các thứ thì có cần thiết kế banner sẵn không hay là khi chèn quảng cáo nó sẽ tự chèn các nguồn như là Ezoic Mediavine Raptive.

1. có các trang terms-of-use, privacy-policy, contact us
2. menu lấy ra menu như wp. menu 2 cấp thôi nhé
3. ngoài menu chính ra có menu nhỏ dưới menu chính sẽ có như là trending sẽ list ra các bài có lượt xem nhiều nhất xuống nhé, các bài mới nhất sẽ theo thời gian đăng
4. ảnh về trang ![alt text](image.png) nhưng tôi nghĩ không cần sidebar bên phải đâu. bạn chỉ cần làm layout giống trang này nhé nội dung hay menu sẽ lấy từ dữ liệu
5. trang này không cần login đâu nhé
6. đây là trang single ![alt text](image-1.png) bạn cũng làm tương tự nhưng sẽ không cần video và Recommended Videos đâu chỉ cần lấy ra các bài liên quan theo category là được trừ bài đang xem ra nhé
7. ![alt text](image-2.png) giống trang này phần bài viết liên quan sẽ sticky ở đầu nhé. nhưng cuộn lên nó nằm dưới menu. có bình luận nhiều lớp khi bình luận cho nhập tên và content là được bình luận load more 10 comment nhé. không có bình luận ảnh. 
8. có tags theo từng post nhé. nếu click vào tags thì list ra các bài có gắng tags đó và sắp xếp theo mới nhất hoặc view nhiều nhất
9. ![alt text](image-3.png) footer có menu nhé và list vào mạng xã hội. chỉ cần cho vào facebook, x, reddit
10. có file 404 cho user button redirect về home nhé

### PHP Extensions cần bật trên host
- **GD extension** (`extension=gd`) — đang bị tắt trên local XAMPP (`C:\xampp\php\php.ini` dòng 931). Đã bật lại để WebP hoạt động. Khi lên host, vào **cPanel → PHP Extensions** (hoặc hỏi host) để đảm bảo `gd` đang ON.
  - **Why:** PHP GD cần thiết để WordPress xử lý ảnh (resize thumbnail, tạo responsive sizes). Thiếu GD thì WebP (và các định dạng khác) không upload/resize được.
