function req=wwwput_my_data(serverweb,block,filelen,userid,descr,dataid,archfilename);

%serverweb='http://127.0.0.1:8080/';
%serverweb='http://localhost:8080/';
url=strcat(serverweb,['/ParalelMatlabServer2/dataputfile_d.php']);%'?uid=' num2str(uid) '&platformid=1']);
%params = {'uid',num2str(uid), 'platformid',num2str(1),'data',hexencode(block),'pos',num2str(filelen),'num_task',num2str(num_task)};
%params = {'pid',num2str(processid), 'platformid',num2str(1),'data',hexencode3(block),'pos',num2str(filelen),'taskid',num2str(num_task),'dataid',num2str(curdataid),'taskgroupid',taskgroupid};
params = {'userid',num2str(userid), 'descr',descr,'dataid',num2str(dataid),'data',hexencode3(block),'pos',num2str(filelen),'archfilename',archfilename}; % 20130116 пробую без таскайди
req=urlread(url,'POST',params);

if (0)
% параметры уже передам запросом пост.
query=['POST ' url ' HTTP/1.0\r\n'];
query=[query,''];
query=[query,'Host: prognoz.ck.ua\r\n'];
query=[query,'Referer: prognoz.ck.ua\r\n'];
query=[query,'Content-type: multipart/form-data, boundary=ccf8111910\r\n'];
query=[query,'Content-length: ' querylen '\r\n\r\n'];

% передаю каждое поле
query=[query,'--ccf8111910\r\n'];
query=[query,'Content-Disposition: form-data; name="offset"\r\n\r\n' num2str(offset) '\r\n'];

query=[query,'--ccf8111910\r\n'];
query=[query,'Content-Disposition: form-data; name="offset"\r\n\r\n' num2str(offset) '\r\n'];

query=[query,'--ccf8111910\r\n'];
query=[query,'Content-Disposition: form-data; name="var_file"; filename="' '"\r\n'];
query=[query,'Content-Type: text/plain\r\n\r\n'];
query=[query,block, '\r\n'];
query=[query,'--ccf8111910\r\n'];



import java.net.Socket;
import java.io.*;
s=Socket('localhost',8080,true)
%s.connect(s)
input_stream   = s.getInputStream
d_input_stream = DataInputStream(input_stream)
output_stream   = s.getOutputStream
d_output_stream = DataOutputStream(output_stream)

%bytes_available = input_stream.available
d_output_stream.writeChars('GET / HTTP/1.1\r\n\r\n');
d_output_stream.flush();

bytes_available = input_stream.available

end



function data_enc=hexencode(data);
n=length(data);
data_enc='';
for i=1:n
    thisbyte=data(i);
    sss=sprintf('%02x',thisbyte);
    data_enc=[data_enc, sss];
end


function data_enc=hexencode2(data);
n=length(data);
data_enc=blanks(n*2);% zeros(1,n*2,'uint8');
for i=1:n
    thisbyte=data(i);
    %sss=sprintf('%02x',thisbyte);
    firstbyte=mod(thisbyte,16);
    if (firstbyte>=10)
        firstbyte=firstbyte+'0';
    else
        firstbyte=firstbyte-10+'a';
    end
    secondbyte=floor(thisbyte/16);
    if (secondbyte>=10)
        secondbyte=secondbyte+'0';
    else
        secondbyte=secondbyte-10+'a';
    end
    data_enc(i*2-1)=secondbyte;
    data_enc(i*2)=firstbyte;
end

function data_enc=hexencode3(data);
n=length(data); 
data_enc=blanks(n*2);% zeros(1,n*2,'uint8');
firstbytes=mod(data,16);
firstbytes_numbers=firstbytes+uint8('0');
firstbytes_needletters=firstbytes>=10;
firstbytes_numbers=firstbytes_numbers+uint8(('a'-'0')*firstbytes_needletters-10);
secondbytes=uint8(floor(double(data)/16));
secondbytes_numbers=secondbytes+uint8('0');
secondbytes_needletters=secondbytes>=10;
secondbytes_numbers=secondbytes_numbers+uint8(('a'-'0')*secondbytes_needletters-10);

data_enc(1:2:n*2)=secondbytes_numbers;
data_enc(2:2:n*2)=firstbytes_numbers;