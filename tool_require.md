1. về trang trang này là wp. giờ tôi muốn thế này. expose api để create post, get category.

2. tạo 1 tool bằng python để thực hiện các thao tác sau:
- tool tạo 1 folder ở đây luôn nhé.
- flow sẽ là: 
+ tôi paste list link bài báo từ các nền tảng news khác vào và ấn run thì bạn sẽ crawl ảnh về thư mục riêng để là ảnh đại diện( featured image) post, nội dung(có thể có ảnh nhé và trừ các phần quảng cáo ra), tiêu đề.
+ sau khi có nội dung và title đưa vào claude để xào nấu lại nội dung khác đi và tăng tính drama lên. nhưng không khẳng định là đúng đâu nhé kiểu chuẩn đoán thôi không khẳng định. xào nấu sao để thành nội dung và tiêu đề riêng không trùng với bài cũ. cái này bạn tạo prompt cho tốt nhé
+ prompt ngoài xào lại nội dung, tiêu đề cần:
*khi tạo vào trang wp check xem list category xem nên thuộc category nào. có thể viết api và tôi cung cấp list data category nếu bạn cần nhé. hỏi trong prompt luôn là với nội dung và tiêu đề này nên thuộc category nào trong đây rồi bạn gửi kèm list category và desc của category đó nữa
*hỏi nên để hastag là gì để tạo tags wp khi đăng bài luôn nhé
+ có nội dung và tiêu đề mới xong nhớ chèn ảnh ở nội dung cũ vào nội dung mới nếu nội dung cũ có nhé. nhưng ảnh đại diện( featured image) bắt buộc có nha và tiến hành tạo vào trang wp. 

+ khi tạo xong bài viết cần xóa ảnh đã tải cho bài viết đó trong thư mục crawl về nhé.


- đây là một số link sẽ crawl data và ảnh của nó nhé chỉ ảnh không cần video đâu nhé và trừ quảng cáo ra:
+ https://www.tmz.com/2026/05/31/shaun-white-date-mystery-woman-photos/
+ https://www.hoopshype.com/story/sports/nba/2026/02/26/celtics-refused-to-tank-the-rest-of-the-nba-should-be-forced-to-follow-heres-how/88875590007/
+ https://clutchpoints.com/nba/nba-stories/nba-finals-schedule-preview-predictions-spurs-knicks
+ https://www.thebiglead.com/#

sẽ là link bài viết từ các trang và bài báo này

- Nếu có bất kỳ câu hỏi nào thì bạn có thể hỏi lại tôi