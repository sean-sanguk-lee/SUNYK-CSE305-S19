DROP DATABASE IF EXISTS CSE305_Final;
CREATE DATABASE CSE305_Final;
GRANT ALL PRIVILEGES ON CSE305_Final.* to root@localhost IDENTIFIED BY 'root';

USE CSE305_Final;


# LOCATION Table
CREATE TABLE LOCATION (
    lid INTEGER AUTO_INCREMENT,
    location VARCHAR(128) NOT NULL,

    PRIMARY KEY (lid)
);

# USERS Table
CREATE TABLE USERS (
    uid INTEGER AUTO_INCREMENT,
    username VARCHAR(32) NOT NULL,
    pw VARCHAR(128) NOT NULL,
    coin INTEGER DEFAULT 0,
    lid INTEGER,

    PRIMARY KEY (uid),
    FOREIGN KEY (lid) REFERENCES LOCATION(lid)
);

# RESTAURANT Table
CREATE TABLE RESTAURANT (
    rid INTEGER AUTO_INCREMENT,
    rname VARCHAR(64) NOT NULL,
    min_price INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (rid)
);

# MENU Table
CREATE TABLE MENU (
    mid INTEGER AUTO_INCREMENT,
    menu VARCHAR(64) NOT NULL,
    price INTEGER NOT NULL,
    splittable BOOLEAN NOT NULL DEFAULT FALSE,

    PRIMARY KEY (mid)
);

# USERS-Orders-WAITLIST Relationship
CREATE TABLE ORDERS (
    oid INTEGER AUTO_INCREMENT,
    uid INTEGER NOT NULL,
    mid INTEGER NOT NULL,
    rid INTEGER NOT NULL,
    lid INTEGER NOT NULL,
    otype BOOLEAN NOT NULL,
    created_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    matched BOOLEAN NOT NULL DEFAULT FALSE,

    PRIMARY KEY (oid),
    FOREIGN KEY (uid) REFERENCES USERS(uid) ON DELETE CASCADE,
    FOREIGN KEY (mid) REFERENCES MENU(mid) ON DELETE CASCADE,
    FOREIGN KEY (rid) REFERENCES RESTAURANT(rid) ON DELETE CASCADE,
    FOREIGN KEY (lid) REFERENCES LOCATION(lid) ON DELETE CASCADE
);

# WAITLIST (Queue) Table
CREATE TABLE WAITLIST (
    qid INTEGER NOT NULL,
    oid INTEGER NOT NULL,

    FOREIGN KEY (oid) REFERENCES ORDERS(oid) ON DELETE CASCADE
);

# COUPON Table
CREATE TABLE COUPON (
    coupon_code INTEGER AUTO_INCREMENT,
    price INTEGER(5) NOT NULL,
    expires DATETIME,

    PRIMARY KEY (coupon_code)
);

# RESTAURANT-can_deliver_to-LOCATION Relationship
CREATE TABLE can_deliver_to (
    rid INTEGER NOT NULL,
    lid INTEGER NOT NULL,

    FOREIGN KEY (rid) REFERENCES RESTAURANT(rid) ON DELETE CASCADE,
    FOREIGN KEY (lid) REFERENCES LOCATION(lid) ON DELETE CASCADE
);

# RESTAURANT-Offers-MENU Relationship
CREATE TABLE OFFERS (
    rid INTEGER NOT NULL,
    mid INTEGER NOT NULL,

    FOREIGN KEY (rid) REFERENCES RESTAURANT(rid) ON DELETE CASCADE,
    FOREIGN KEY (mid) REFERENCES MENU(mid) ON DELETE CASCADE
);

# USERS-Owns-COUPON Relationship
CREATE TABLE OWNS (
    uid INTEGER NOT NULL,
    coupon_code INTEGER(8) NOT NULL,

    FOREIGN KEY (uid) REFERENCES USERS(uid) ON DELETE CASCADE,
    FOREIGN KEY (coupon_code) REFERENCES COUPON(coupon_code) ON DELETE CASCADE
);



#################################################
################### TRIGGERS ####################

CREATE TRIGGER coupon_expire_date
    BEFORE INSERT ON COUPON
    FOR EACH ROW
        SET NEW.expires = DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 7 DAY);

-- 1. After Insert into Orders,
-- 2. Get a Waitlist tuple that waitlist.qid = orders.qid
    -- With Waitlist.rid, search Restaurant table
    -- Let m = min_price
-- 3. Search Orders with Orders.qid (inserted at step 1)
    -- Get the sum of prices by searching from Menu table with Order.mid (inserted at step 1)
-- 4, If a sum (from step 3) >= m (from step 2),
    -- set Waitlist.matched = true
-- DELIMITER $$
-- CREATE TRIGGER update_matched_in_waitlist
--     AFTER INSERT ON ORDERS
--     FOR EACH ROW
-- BEGIN
--     SET @ins_qid = (SELECT qid FROM ORDERS o WHERE o.oid = (SELECT LAST_INSERT_ID())),
--         @ins_mid = (SELECT mid FROM ORDERS o WHERE o.oid = (SELECT LAST_INSERT_ID())),
--         @ins_rid = (SELECT rid FROM OFFERS of WHERE of.mid = @ins_mid),
--         @ins_uid = (SELECT uid FROM ORDERS o WHERE o.oid = (SELECT LAST_INSERT_ID())),
--         @min_price = 0, @w_psum = 0;

--     IF @ins_qid IS NULL THEN -- Initial insert is creating the waitlist
--         INSERT INTO WAITLIST(rid, location, is_group)
--         VALUES ((SELECT @ins_rid),
--                 (SELECT location FROM USERS u WHERE u.uid = @ins_uid),
--                 (SELECT splittable FROM Menu m WHERE m.mid = @ins_mid));
--     end if;
--         SET @min_price = (SELECT min_price FROM RESTAURANT r
--                                             WHERE r.rid = (SELECT rid FROM WAITLIST w WHERE w.qid = @ins_qid));
--         SET @w_psum = (SELECT SUM(price) FROM MENU m, (SELECT mid FROM ORDERS o WHERE o.qid = @ins_qid)p
--                                                     WHERE m.mid = p.mid);

--         IF @min_price <= @w_psum THEN
--             UPDATE WAITLIST w SET matched = TRUE WHERE w.qid = @ins_qid;
--         END IF;
--     -- END IF;

-- END$$
-- DELIMITER ;


#################################################
################### Populate ####################
# LOCATION                                                                  # lid
INSERT INTO LOCATION (location) VALUES ("Dormitory Bldg A Bus Stop");       # 1
INSERT INTO LOCATION (location) VALUES ("Dormitory Bldg B ATM");            # 2
INSERT INTO LOCATION (location) VALUES ("Parking Lot C");                   # 3
INSERT INTO LOCATION (location) VALUES ("Academic Building A");             # 4
INSERT INTO LOCATION (location) VALUES ("Academic Building B");             # 5
INSERT INTO LOCATION (location) VALUES ("CS Commons");                      # 6
INSERT INTO LOCATION (location) VALUES ("LEAD Lab");                        # 7
INSERT INTO LOCATION (location) VALUES ("Game Lab");                        # 8
INSERT INTO LOCATION (location) VALUES ("IGC Guesthouse");                  # 9

# RESTAURANT                                                                                # rid
INSERT INTO RESTAURANT (rname, min_price) VALUES ("Kimbob Heaven", 15000);                  # 1
INSERT INTO RESTAURANT (rname, min_price) VALUES ("Crazy Tteokbokki", 14000);               # 2
INSERT INTO RESTAURANT (rname, min_price) VALUES ("Fell in Love With Sushi", 23000);        # 3
INSERT INTO RESTAURANT (rname, min_price) VALUES ("Brothers' Pizza", 12500);                # 4
INSERT INTO RESTAURANT (rname, min_price) VALUES ("Thunder Chicken", 14000);                # 5
INSERT INTO RESTAURANT (rname, min_price) VALUES ("Dami's Philanthrophy", 20000);           # 6
INSERT INTO RESTAURANT (rname, min_price) VALUES ("Hwagye", 22000);                         # 7

# MENU                                                                                                # mid
INSERT INTO MENU (menu, price, splittable) VALUES ("Tuna Kimbob", 3000, FALSE);                       # 1
INSERT INTO MENU (menu, price, splittable) VALUES ("Ramen Tteokbokki", 4000, FALSE);                  # 2
INSERT INTO MENU (menu, price, splittable) VALUES ("King-Sized Pork Cutlet", 6500, FALSE);            # 3
INSERT INTO MENU (menu, price, splittable) VALUES ("Spicy Pork Bowl", 6500, FALSE);                   # 4
INSERT INTO MENU (menu, price, splittable) VALUES ("Tuna Kimchi Soup", 6500, FALSE);                  # 5
INSERT INTO MENU (menu, price, splittable) VALUES ("Kimchi Fried Rice", 6500, FALSE);                 # 6
INSERT INTO MENU (menu, price, splittable) VALUES ("Crazy Teokbokki Set A", 17900, TRUE);
INSERT INTO MENU (menu, price, splittable) VALUES ("Crazy Teokbokki Set B", 19000, TRUE);
INSERT INTO MENU (menu, price, splittable) VALUES ("Crazy Teokbokki", 14000, FALSE);
INSERT INTO MENU (menu, price, splittable) VALUES ("Sushi Set A", 26000, FALSE);
INSERT INTO MENU (menu, price, splittable) VALUES ("Sashimi Set A", 40000, FALSE);
INSERT INTO MENU (menu, price, splittable) VALUES ("Hand-Made Fried Shrimp", 15000, TRUE);
INSERT INTO MENU (menu, price, splittable) VALUES ("Assorted Fries Set", 19000, FALSE);
INSERT INTO MENU (menu, price, splittable) VALUES ("Sushi Bento For One", 19000, FALSE);
INSERT INTO MENU (menu, price, splittable) VALUES ("Men's Pizza", 17700, TRUE);
INSERT INTO MENU (menu, price, splittable) VALUES ("Pepperoni Pizza", 18800, TRUE);
INSERT INTO MENU (menu, price, splittable) VALUES ("Sweet Potato Pizza", 20900, TRUE);
INSERT INTO MENU (menu, price, splittable) VALUES ("Bulgogi Pizza", 19900, TRUE);
INSERT INTO MENU (menu, price, splittable) VALUES ("Real Tomato Pizza", 19900, TRUE);
INSERT INTO MENU (menu, price, splittable) VALUES ("Meat Lover's Pizza", 24800, TRUE);
INSERT INTO MENU (menu, price, splittable) VALUES ("Boneless Fried Chicken", 18000, FALSE);
INSERT INTO MENU (menu, price, splittable) VALUES ("Boneless Spicy Chicken", 19000, FALSE);
INSERT INTO MENU (menu, price, splittable) VALUES ("Fried Chicken", 17000, FALSE);
INSERT INTO MENU (menu, price, splittable) VALUES ("Garlic Fried Chicken", 20000, FALSE);
INSERT INTO MENU (menu, price, splittable) VALUES ("Green Onion Chicken", 19000, FALSE);
INSERT INTO MENU (menu, price, splittable) VALUES ("Joong-Joong Set", 30000, TRUE);
INSERT INTO MENU (menu, price, splittable) VALUES ("Joong-Dae Set", 33000, FALSE);
INSERT INTO MENU (menu, price, splittable) VALUES ("So-Joong Set", 27000, FALSE);
INSERT INTO MENU (menu, price, splittable) VALUES ("A Bowl of Rice", 1000, FALSE);
INSERT INTO MENU (menu, price, splittable) VALUES ("Spicy Boneless Chicken Feet", 17000, FALSE);
INSERT INTO MENU (menu, price, splittable) VALUES ("Spicy Boneless Soupy Chicken Feet", 18000, FALSE);
INSERT INTO MENU (menu, price, splittable) VALUES ("Fire Chicken", 17000, FALSE);

# RESTAURANT can_deliver_to LOCATION
INSERT INTO can_deliver_to (rid, lid) VALUES (1, 6); # "CS Commons"
INSERT INTO can_deliver_to (rid, lid) VALUES (1, 3); # "Parking Lot C"
INSERT INTO can_deliver_to (rid, lid) VALUES (1, 1); # "Dormitory Bldg A Bus Stop"
INSERT INTO can_deliver_to (rid, lid) VALUES (1, 2); # "Dormitory Bldg B ATM");
INSERT INTO can_deliver_to (rid, lid) VALUES (2, 2); # "Dormitory Bldg B ATM");
INSERT INTO can_deliver_to (rid, lid) VALUES (2, 3); # "Parking Lot C"
INSERT INTO can_deliver_to (rid, lid) VALUES (3, 6); # "CS Commons"
INSERT INTO can_deliver_to (rid, lid) VALUES (3, 7); # "LEAD Lab"
INSERT INTO can_deliver_to (rid, lid) VALUES (3, 8); # "Game Lab"
INSERT INTO can_deliver_to (rid, lid) VALUES (3, 3); # "Parking Lot C"
INSERT INTO can_deliver_to (rid, lid) VALUES (4, 6); # "CS Commons"
INSERT INTO can_deliver_to (rid, lid) VALUES (4, 7); # "LEAD Lab"
INSERT INTO can_deliver_to (rid, lid) VALUES (4, 8); # "Game Lab"
INSERT INTO can_deliver_to (rid, lid) VALUES (4, 2); # "Dormitory Bldg B ATM");
INSERT INTO can_deliver_to (rid, lid) VALUES (4, 3); # "Parking Lot C"
INSERT INTO can_deliver_to (rid, lid) VALUES (5, 1); # "Dormitory Bldg A Bus Stop"
INSERT INTO can_deliver_to (rid, lid) VALUES (5, 2); # "Dormitory Bldg B ATM");
INSERT INTO can_deliver_to (rid, lid) VALUES (5, 3); # "Parking Lot C"
INSERT INTO can_deliver_to (rid, lid) VALUES (5, 4); # "Academic Building A"
INSERT INTO can_deliver_to (rid, lid) VALUES (5, 5); # "Academic Building B"
INSERT INTO can_deliver_to (rid, lid) VALUES (5, 6); # "CS Commons"
INSERT INTO can_deliver_to (rid, lid) VALUES (5, 7); # "LEAD Lab"
INSERT INTO can_deliver_to (rid, lid) VALUES (5, 8); # "Game Lab"
INSERT INTO can_deliver_to (rid, lid) VALUES (5, 9); # "IGC Guesthouse"
INSERT INTO can_deliver_to (rid, lid) VALUES (6, 1); # "Dormitory Bldg A Bus Stop"
INSERT INTO can_deliver_to (rid, lid) VALUES (6, 2); # "Dormitory Bldg B ATM");
INSERT INTO can_deliver_to (rid, lid) VALUES (6, 3); # "Parking Lot C"
INSERT INTO can_deliver_to (rid, lid) VALUES (6, 4); # "Academic Building A"
INSERT INTO can_deliver_to (rid, lid) VALUES (7, 1); # "Dormitory Bldg A Bus Stop"
INSERT INTO can_deliver_to (rid, lid) VALUES (7, 2); # "Dormitory Bldg B ATM");
INSERT INTO can_deliver_to (rid, lid) VALUES (7, 3); # "Parking Lot C"

INSERT INTO LOCATION (location) VALUES ("Dormitory Bldg A Bus Stop");       # 1
INSERT INTO LOCATION (location) VALUES ("Dormitory Bldg B ATM");            # 2
INSERT INTO LOCATION (location) VALUES ("Parking Lot C");                   # 3
INSERT INTO LOCATION (location) VALUES ("Academic Building A");             # 4
INSERT INTO LOCATION (location) VALUES ("Academic Building B");             # 5
INSERT INTO LOCATION (location) VALUES ("CS Commons");                      # 6
INSERT INTO LOCATION (location) VALUES ("LEAD Lab");                        # 7
INSERT INTO LOCATION (location) VALUES ("Game Lab");                        # 8
INSERT INTO LOCATION (location) VALUES ("IGC Guesthouse");                  # 9

# RESTAURANT-Offers-MENU
INSERT INTO OFFERS (rid, mid) VALUES (1, 1);
INSERT INTO OFFERS (rid, mid) VALUES (1, 2);
INSERT INTO OFFERS (rid, mid) VALUES (1, 3);
INSERT INTO OFFERS (rid, mid) VALUES (1, 4);
INSERT INTO OFFERS (rid, mid) VALUES (1, 5);
INSERT INTO OFFERS (rid, mid) VALUES (1, 6);
INSERT INTO OFFERS (rid, mid) VALUES (2, 7);
INSERT INTO OFFERS (rid, mid) VALUES (2, 8);
INSERT INTO OFFERS (rid, mid) VALUES (2, 9);
INSERT INTO OFFERS (rid, mid) VALUES (3, 10);
INSERT INTO OFFERS (rid, mid) VALUES (3, 11);
INSERT INTO OFFERS (rid, mid) VALUES (3, 12);
INSERT INTO OFFERS (rid, mid) VALUES (3, 13);
INSERT INTO OFFERS (rid, mid) VALUES (3, 14);
INSERT INTO OFFERS (rid, mid) VALUES (4, 15);
INSERT INTO OFFERS (rid, mid) VALUES (4, 16);
INSERT INTO OFFERS (rid, mid) VALUES (4, 17);
INSERT INTO OFFERS (rid, mid) VALUES (4, 18);
INSERT INTO OFFERS (rid, mid) VALUES (4, 19);
INSERT INTO OFFERS (rid, mid) VALUES (4, 20);
INSERT INTO OFFERS (rid, mid) VALUES (5, 21);
INSERT INTO OFFERS (rid, mid) VALUES (5, 22);
INSERT INTO OFFERS (rid, mid) VALUES (5, 23);
INSERT INTO OFFERS (rid, mid) VALUES (5, 24);
INSERT INTO OFFERS (rid, mid) VALUES (5, 25);
INSERT INTO OFFERS (rid, mid) VALUES (6, 26);
INSERT INTO OFFERS (rid, mid) VALUES (6, 27);
INSERT INTO OFFERS (rid, mid) VALUES (6, 28);
INSERT INTO OFFERS (rid, mid) VALUES (6, 29);
INSERT INTO OFFERS (rid, mid) VALUES (7, 30);
INSERT INTO OFFERS (rid, mid) VALUES (7, 31);
INSERT INTO OFFERS (rid, mid) VALUES (7, 32);

# USERS                                                                                                 # uid
INSERT INTO USERS (username, pw, coin, lid) VALUES ("sLee", "sLee123", 200000, 8); # "Game Lab"        # 1
INSERT INTO USERS (username, pw, coin, lid) VALUES ("jWoo", "jWoo123", 200000, 6); # "CS Commons"      # 2
INSERT INTO USERS (username, pw, coin, lid) VALUES ("bkoo", "bKoo123", 200000, 3); # "Parking Lot C"   # 3


# WAITLIST
INSERT INTO ORDERS (uid, mid, rid, lid, otype) VALUES (2, 3, 1, 3, TRUE);		# jWoo, King-Sized Pork Cutlet, Kimbob Heaven, Parking Lot C, GroupOrder
INSERT INTO WAITLIST (qid, oid) VALUES (1, 1);
INSERT INTO ORDERS (uid, mid, rid, lid, otype) VALUES (1, 6, 1, 3, TRUE); 		# sLee, Kimchi Fried Rice, Kimbob Heaven, Parking Lot C, GroupOrder
INSERT INTO WAITLIST (qid, oid) VALUES (1, 2);
INSERT INTO ORDERS (uid, mid, rid, lid, otype) VALUES (3, 15, 4, 4, FALSE);		# bKoo, Man's Pizza, Brother's Pizza, Academic Building A, SplitOrder
INSERT INTO WAITLIST (qid, oid) VALUES (2, 3);
INSERT INTO ORDERS (uid, mid, rid, lid, otype) VALUES (1, 15, 4, 4, FALSE);		# sLee, Man's Pizza, Brother's Pizza, Academic Building A, SplitOrder
INSERT INTO WAITLIST (qid, oid) VALUES (2, 4);

INSERT INTO coupon(price) VALUES(5000);
INSERT INTO coupon(price) VALUES(5000);
INSERT INTO OWNS(uid, coupon_code) VALUES(2, 1);
INSERT INTO OWNS(uid, coupon_code) VALUES(2, 2);
