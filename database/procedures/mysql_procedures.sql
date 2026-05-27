-- =====================================================
-- MySQL procedūras (izpildei MySQL/MariaDB vidē)
-- =====================================================

-- 1. Procedūra: aizņemties grāmatu (ar transakciju)
DELIMITER //
CREATE PROCEDURE BorrowBook(
    IN p_book_id INT,
    IN p_reader_id INT,
    IN p_borrowed_at DATE
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    IF (SELECT available_copies FROM books WHERE id = p_book_id FOR UPDATE) <= 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Nav pieejamu eksemplāru.';
    END IF;

    INSERT INTO borrowings (book_id, reader_id, borrowed_at, created_at, updated_at)
    VALUES (p_book_id, p_reader_id, p_borrowed_at, NOW(), NOW());

    UPDATE books
    SET available_copies = available_copies - 1, updated_at = NOW()
    WHERE id = p_book_id;

    COMMIT;
END //
DELIMITER ;

-- 2. Procedūra: atgriezt grāmatu
DELIMITER //
CREATE PROCEDURE ReturnBook(
    IN p_borrowing_id INT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    IF (SELECT returned_at FROM borrowings WHERE id = p_borrowing_id FOR UPDATE) IS NOT NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Grāmata jau ir atdota.';
    END IF;

    UPDATE borrowings
    SET returned_at = CURDATE(), updated_at = NOW()
    WHERE id = p_borrowing_id;

    UPDATE books b
    JOIN borrowings bw ON bw.book_id = b.id
    SET b.available_copies = b.available_copies + 1, b.updated_at = NOW()
    WHERE bw.id = p_borrowing_id;

    COMMIT;
END //
DELIMITER ;

-- 3. Procedūra: lasītāja vēsture
DELIMITER //
CREATE PROCEDURE GetReaderHistory(
    IN p_reader_id INT
)
BEGIN
    SELECT
        b.title AS book_title,
        bw.borrowed_at,
        bw.returned_at
    FROM borrowings bw
    JOIN books b ON b.id = bw.book_id
    WHERE bw.reader_id = p_reader_id
    ORDER BY bw.borrowed_at DESC;
END //
DELIMITER ;

-- 4. Procedūra: populārākās grāmatas
DELIMITER //
CREATE PROCEDURE GetMostBorrowedBooks(
    IN p_limit INT
)
BEGIN
    SELECT
        b.id,
        b.title,
        b.isbn,
        COUNT(bw.id) AS borrow_count
    FROM books b
    LEFT JOIN borrowings bw ON bw.book_id = b.id
    GROUP BY b.id, b.title, b.isbn
    ORDER BY borrow_count DESC
    LIMIT p_limit;
END //
DELIMITER ;

-- 5. Procedūra: lasītāja soda naudas aprēķins (0.50 EUR par kavēto dienu)
DELIMITER //
CREATE PROCEDURE CalculateReaderFine(
    IN p_reader_id INT,
    OUT p_total_fine DECIMAL(10,2),
    OUT p_overdue_count INT
)
BEGIN
    SELECT
        COALESCE(SUM(
            DATEDIFF(CURDATE(), DATE_ADD(bw.borrowed_at, INTERVAL 14 DAY)) * 0.50
        ), 0),
        COUNT(*)
    INTO p_total_fine, p_overdue_count
    FROM borrowings bw
    WHERE bw.reader_id = p_reader_id
      AND bw.returned_at IS NULL
      AND DATE_ADD(bw.borrowed_at, INTERVAL 14 DAY) < CURDATE();
END //
DELIMITER ;
