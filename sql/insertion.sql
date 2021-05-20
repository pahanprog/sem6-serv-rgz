INSERT INTO country(name)
VALUES
('UK'),
('Germany'),
('France'),
('USA'),
('Italy'),
('Japan'),
('Korea'),
('South Korea'),
('Russia'),
('Sweden');


INSERT INTO brand(brandname, countryid, foundationyear, companyvalue)
VALUES
('Aston Martin',11,1913, 997300000),
('Audi',12,1909,38137170852),
('Bentley',11,1919,485200000),
('BMW', 12, 1916, 49800000000),
('Bugatti',13,1909,19739868750),
('Cadillac',14,1902,56950000000),
('Chevrolet', 14, 1911, 55000000000),
('Citroen', 13, 1919, 19739868750),
('Dodge',14,1900,19739868750),
('Ferrari',15,1947,35000000000),
('FIAT', 15, 1899, 25105000000),
('Ford', 14, 1903, 35000000000),
('Honda', 16, 1948, 50998554624),
('Hyundai', 17, 1967, 22926084096),
('Jaguar',11,1922,6610000000),
('KIA', 17, 1944, 14804106426),
('LADA', 19, 1963, 829488000),
('Lamborghini',15,1963,13377104896),
('Land Rover', 11, 1948, 6610000000),
('Lexus', 16, 1989, 3500000000),
('Mazda', 16, 1920, 5462771200),
('Toyota', 16, 1937,  202329292800),
('Nissan', 16, 1933, 24000000000),
('Mercedes-Benz', 12, 1883, 56950000000),
('Opel', 12, 1862, 19739868750),
('Volkswagen', 12, 1937, 129509021250),
('Renault', 13, 1898, 13377104896),
('Peugeot', 13, 1896, 19739868750),
('Mitsubishi', 16, 1870, 6689921024),
('Tesla',14,2003,24000000000);

INSERT INTO Drive(Name)
VALUES
('front'),
('rear'),
('front+rear');


INSERT INTO model(Name,CarBody,Year,BrandId,Price,Seats,DriveId,EngineType,TopSpeed,Acceleration)
VALUES
('DB11','Coupe',2016,11,271914,4,5,'fuel',322,3.9),
('DB11','Cabriolet',2018,11,259908,4,5,'fuel',300,4.1),
('DB9','Coupe',2004,11,215290,4,5,'fuel',300,4.9),
('DB9','Coupe',2012,11,258501,4,5,'fuel',295,4.6),
('Vantage','Coupe',2018,11,211000,2,5,'fuel',314,3.6),
('90','Sedan',1984,12,20908,5,4,'fuel',187,9.5),
('90','Sedan',1987,12,27755,5,4,'fuel',196,10.2),
('A1','Hatchback',2010,12,22550,4,4,'fuel',180,11.7),
('A1','Hatchback',2015,12,25190,4,4,'fuel',215,7.8),
('A1 Sportback','Hatchback',2015,12,27700,4,4,'fuel',203,9.5),
('Quattro','Sedan',1986,12,66567,5,6,'fuel',222,6.7),
('Cabriolet','Cabriolet',1991,12,43109,5,4,'fuel',198,10.8),
('R8','Coupe',2016,12,233720,2,6,'fuel',320,3.5);

INSERT INTO UserRole(Name) 
VALUES
('User'),
('Admin')