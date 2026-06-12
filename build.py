import os
import re
import shutil

SOURCE_DIR = '.'
DIST_DIR = 'dist'
ASSETS = ['css', 'images', 'js', 'favicon.ico']

def compile_shtml(file_path):
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()

    # Regex to find SSI includes: <!--#include file="include/meta.html"-->
    pattern = re.compile(r'<!--#include\s+(?:file|virtual)="([^"]+)"\s*-->')
    
    def replace_include(match):
        include_path = match.group(1)
        # Ensure include path is relative to source root
        full_path = os.path.join(SOURCE_DIR, include_path)
        if os.path.exists(full_path):
            with open(full_path, 'r', encoding='utf-8') as inc_f:
                return inc_f.read()
        else:
            print(f"Warning: Include file not found: {full_path}")
            return ""

    # Replace recursively in case includes have their own includes
    prev_content = None
    while prev_content != content:
        prev_content = content
        content = pattern.sub(replace_include, content)
        
    return content

def main():
    print(f"Starting build process...")
    if os.path.exists(DIST_DIR):
        shutil.rmtree(DIST_DIR)
    os.makedirs(DIST_DIR)

    # Copy assets
    for asset in ASSETS:
        src = os.path.join(SOURCE_DIR, asset)
        dst = os.path.join(DIST_DIR, asset)
        if os.path.exists(src):
            if os.path.isdir(src):
                shutil.copytree(src, dst)
            else:
                shutil.copy2(src, dst)
            print(f"Copied asset: {asset}")

    # Process SHTML files
    for filename in os.listdir(SOURCE_DIR):
        if filename.endswith('.shtml'):
            html_filename = filename.replace('.shtml', '.html')
            compiled_content = compile_shtml(os.path.join(SOURCE_DIR, filename))
            
            # Rewrite .shtml links to .html for the compiled static site
            compiled_content = compiled_content.replace('.shtml', '.html')
            
            # Write to dist/
            with open(os.path.join(DIST_DIR, html_filename), 'w', encoding='utf-8') as f:
                f.write(compiled_content)
            print(f"Compiled {filename} -> {DIST_DIR}/{html_filename}")
            
    print("Build complete! Files are ready in the 'dist' directory.")

if __name__ == '__main__':
    main()
