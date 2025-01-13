import os

def replace_text_in_file(file_path, old_text, new_text):
    with open(file_path, 'r', encoding='utf-8') as file:
        content = file.read()
    
    # Replace the target string
    content = content.replace(old_text, new_text)
    
    with open(file_path, 'w', encoding='utf-8') as file:
        file.write(content)

def replace_in_directory(directory, old_text="http://127.0.0.1:5000", new_text=""):
    for root, dirs, files in os.walk(directory):
        for file in files:
            # Process only files with .js, .php, .html extensions
            if file.endswith(('.js', '.php', '.html')):
                file_path = os.path.join(root, file)
                replace_text_in_file(file_path, old_text, new_text)
                print(f"Replaced in {file_path}")

# Specify the directory where your files are located
directory_path = "./"

replace_in_directory(directory_path)
