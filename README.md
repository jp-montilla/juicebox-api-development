# RESTful API Development

A simple project built to showcase Laravel API development, authentication, database design, external API, and adherence to best practice.

### Built With

- **[Laravel](https://laravel.com/)**
- **[PHPUnit](https://phpunit.de/index.html)**
- **[Open Weather Map API](https://openweathermap.org/api)**


### Installation
Please follow the following steps for successful installation:

1. **Clone GitHub repo for this project locally**
   
    ```
    git clone https://github.com/jp-montilla/juicebox-api-development.git
    ```
    
- To get the link to the repo, just visit the github page and click on the green “code” button on the right hand side. This will reveal a url that you will replace in the linktogithub.com part of the snippet above.

    ![Screenshot of the project git clone](https://content.pstmn.io/c00f494b-104e-4cb0-9547-361e357ec112/cHJvamVjdCBnaXQucG5n)


2. **Cd into your project**
    ```
    cd <projectName>
    ```

3. **Install Composer Dependencies**
    ```
    composer install
    ```

4. **Create an empty database for the application** using the database tools you prefer. In our example we created a database called "test_database". Just create an empty database.
   
5. **Create an .env file**
    ```
    cp .env.example .env
    ```
- In the .env file, add database information to allow Laravel to connect to the database.
  
    ```
    DB_CONNECTION=<your_database_connection>
    DB_HOST=<your_host>
    DB_PORT=<your_port>
    DB_DATABASE=test_database
    DB_USERNAME=<your_username>
    DB_PASSWORD=<your_password>
    ```
6. **Migrate the database.** Once your credentials are in the .env file, now you can migrate your database
   ```
    php artisan migrate
   ```

7. **Get Open Weather Map API Key**
   - Navigate to **[Open Weather](https://openweathermap.org/)** and create an account.
     
   - Once account has been created navigate to **[API Keys](https://home.openweathermap.org/api_keys)** and create an API Key
     
       ![openweathermapapi](https://github.com/user-attachments/assets/2fc0a69c-b06f-4932-a7e1-86c7a5034c40)

   - Navigate to **[Current Weather Data](https://openweathermap.org/current)** doc and copy the base uri for the API call

       ![base api uri](https://github.com/user-attachments/assets/9f30963d-59fc-4fdf-99d9-90b215519d88)

8. **Set Up Weather API Configuration**
    - Navigate to **.env** file and add these information
      
      ```
      WEATHER_MAP_THIRD_PARTY_API="openweathermap"
      WEATHER_MAP_API_URL=<your_api_url>
      WEATHER_MAP_API_KEY=<your_api_key>
      DEFAULT_WEATHER_CITY=<your_city>
      ```

   - Navigate to **constants.php** file in '/config' directory and update these information

     ```
     'weather_third_party_api' => env('WEATHER_MAP_THIRD_PARTY_API', 'openweathermap'),
     'weather_api_url' => env('WEATHER_MAP_API_URL', <your_api_url>),
     'weather_api_key' => env('WEATHER_MAP_API_KEY', <your_api_key>),
     'weather_default_city' => env('DEFAULT_WEATHER_CITY', <your_city>),
     ```


> [!TIP]
> Here are the example api calls with different query params

```
https://api.openweathermap.org/data/2.5/weather?lat={lat}&lon={lon}&appid={API key}

https://api.openweathermap.org/data/2.5/weather?q={city name}&appid={API key}

https://api.openweathermap.org/data/2.5/weather?q={city name},{country code}&appid={API key}

https://api.openweathermap.org/data/2.5/weather?q={city name},{state code},{country code}&appid={API key}
```
    

9. **Create a cache file for faster configuration loading**

    ```
    php artisan config:cache
    ```

10. **Run application**

    ```
    php artisan serve
    ```




