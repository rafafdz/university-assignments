library IEEE;
use IEEE.STD_LOGIC_1164.ALL;
use IEEE.std_logic_unsigned.all;

entity RegisterCU is
    Port ( clock    : in  std_logic;                        -- Se�al del clock (reducido).
           datain   : in  std_logic_vector (11 downto 0);   -- Se�ales de entrada de datos.
           dataout  : out std_logic_vector (11 downto 0);
           lit : out std_logic_vector (11 downto 0);
           selAddSec      : out std_logic;
           stalling      : out std_logic);  -- Se�ales de salida de datos.
end RegisterCU;

architecture Behavioral of RegisterCU is

signal reg : std_logic_vector(11 downto 0) := (others => '0'); -- Se�ales del registro. Parten en 0.
signal lit_signal : std_logic_vector(11 downto 0) := (others => '0'); 
signal cargar : std_logic_vector(1 downto 0);
signal mux : std_logic;
signal opcode_signal : std_logic_vector(11 downto 0) := (others => '0');
signal ceros  : std_logic_vector(11 downto 0) := (others => '0');
signal opcode_mux : std_logic_vector(1 downto 0) := (others => '0'); 
signal stop : std_logic := '1';

begin

reg_prosses : process (clock)           -- Proceso sensible a clock.
        begin
          if (rising_edge(clock)) then  -- Condici�n de flanco de subida de clock.
            if (cargar = "00") then
                cargar <= "01";
                stop <= '0';
                mux <= '1';
            elsif (cargar = "01") then
                opcode_signal <= datain;
                cargar <= "10";
                stop <= '1';
                mux <= '1';
            elsif (cargar = "10") then
                lit_signal <= datain;
                cargar <= "00";
                mux <= '1';
                stop <= '0';
            end if;
          else
                -- necesario para poder simular 
          end if;
        end process;

with cargar select
    dataout <=  ceros when "10",
                opcode_signal when "00",
                ceros when "01",
                ceros when "11" ;

selAddSec <= mux;
stalling <= stop;
lit <= lit_signal;
end Behavioral;