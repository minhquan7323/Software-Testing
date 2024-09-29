import requests
import os
save_dir = "img"

base_url = "https://media3.coolmate.me/cdn-cgi/image/quality=80,format=auto/uploads/January2023/Thiet_ke_chua_co_ten_(1).jpg"

os.makedirs(save_dir, exist_ok=True)

for i in range(1, 2):
    image_url = f"{base_url}"
    response = requests.get(image_url)
        
    if response.status_code == 200:
        file_name = os.path.join(save_dir, f"Túi Tote 84RISING Denim.jpg")
        with open(file_name, 'wb') as file:
            file.write(response.content)
        print(f"Downloaded {file_name}")
    else:
        print(f"Failed to download {image_url}")
print("Finished downloading images.")
