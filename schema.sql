DROP TABLE IF EXISTS product;
CREATE TABLE product (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100)  NOT NULL,
    category    VARCHAR(50)   NOT NULL,
    price       DECIMAL(10,2) NOT NULL,
    quantity    INT           NOT NULL,
    description VARCHAR(500)  NOT NULL
);

DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_name  VARCHAR(50)  NOT NULL UNIQUE,
    password   CHAR(128)    NOT NULL,        
    email      VARCHAR(100),
    first_name VARCHAR(50),
    last_name  VARCHAR(50),
    is_admin   VARCHAR(20)  NOT NULL DEFAULT 'no'
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'completed',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price_at_purchase DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES product(id)
);



INSERT INTO users (user_name, password, email, first_name, last_name, is_admin) VALUES
    ('alanpeter', SHA2('alanpeter88', 512), 'alan.peter@uwm.edu',    'Alan', 'Peter', 'no'),
    ('alanadmin', SHA2('alanadmin99', 512), 'alan.admin@bookhaven.com', 'Alan', 'Peter', 'yes');


INSERT INTO product (name, category, price, quantity, description) VALUES
  ('To Kill a Mockingbird',                  'Fiction',     12.99, 25, 'Harper Lee classic about racial injustice in the American South.'),
  ('1984',                                    'Fiction',     10.99, 30, 'George Orwell dystopian novel about totalitarianism and surveillance.'),
  ('The Great Gatsby',                        'Fiction',      9.99, 20, 'F. Scott Fitzgerald story of wealth and the American dream.'),
  ('Sapiens: A Brief History of Humankind',  'Non-Fiction', 18.99, 18, 'Yuval Noah Harari sweeping account of how humans came to dominate the planet.'),
  ('Educated',                                'Non-Fiction', 16.50, 15, 'Tara Westover memoir of self-education and family.'),
  ('Atomic Habits',                           'Self-Help',   14.95, 40, 'James Clear practical guide to building good habits and breaking bad ones.'),
  ('The Hobbit',                              'Fantasy',     11.99, 22, 'J.R.R. Tolkien adventure of Bilbo Baggins on his journey to the Lonely Mountain.'),
  ('Dune',                                    'Sci-Fi',      15.99, 17, 'Frank Herbert epic of the desert planet Arrakis and the spice that fuels an empire.'),
  ('Becoming',                                'Biography',   19.99, 12, 'Michelle Obama memoir spanning her journey from the South Side of Chicago to the White House.'),
  ('Where the Crawdads Sing',                 'Mystery',     13.49, 16, 'Delia Owens mystery set in the marshes of North Carolina.'),
  ('Harry Potter and the Sorcerer''s Stone', 'Children',     9.99, 35, 'J.K. Rowling first book in the Harry Potter series; a young boy discovers he is a wizard.'),
  ('The Pragmatic Programmer',                'Tech',        29.99,  8, 'Hunt and Thomas classic guide to software craftsmanship.'),
  ('A Brief History of Time',                 'Non-Fiction', 17.50, 14, 'Stephen Hawking introduction to cosmology for general readers.'),
  ('The Lord of the Rings',                   'Fantasy',     24.99, 10, 'J.R.R. Tolkien complete one-volume edition of the epic trilogy.'),
  ('Project Hail Mary',                       'Sci-Fi',      16.95, 13, 'Andy Weir story of a lone astronaut tasked with saving humanity.'),
  ('Mockingjay',                              'Children',    10.50, 18, 'Suzanne Collins final book in the Hunger Games trilogy.'),
  ('Steve Jobs',                              'Biography',   22.00,  9, 'Walter Isaacson authorized biography of the Apple co-founder.'),
  ('Gone Girl',                               'Mystery',     12.49, 11, 'Gillian Flynn psychological thriller about a marriage gone wrong.'),
  ('The 7 Habits of Highly Effective People','Self-Help',    15.99, 20, 'Stephen R. Covey perennial bestseller on personal and professional effectiveness.'),
  ('Clean Code',                              'Tech',        34.99,  6, 'Robert C. Martin guide to writing readable, maintainable software.');

