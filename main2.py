import os

# Specify the root directory to search and output file
root_dir = "."  # Current directory, change if needed
output_file = "php_files_list.txt"

# Open the output file
with open(output_file, 'w', encoding='utf-8') as f:
    # Walk through all subdirectories
    for dirpath, _, filenames in os.walk(root_dir):
        # Filter for PHP files
        php_files = [file for file in filenames if file.endswith('.php')]
        
        # Write each PHP file as a titled section
        for php_file in php_files:
            file_path = os.path.join(dirpath, php_file)
            f.write(f"--- {php_file} ---\n")
            f.write(f"Path: {file_path}\n\n")
            # Read and write the content of the PHP file
            try:
                with open(file_path, 'r', encoding='utf-8') as php:
                    f.write(php.read())
                    f.write("\n\n")
            except Exception as e:
                f.write(f"Error reading file: {e}\n\n")

print(f"PHP files list has been written to {output_file}")